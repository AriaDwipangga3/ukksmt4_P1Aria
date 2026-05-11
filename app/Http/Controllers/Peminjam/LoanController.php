<?php
namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\Loan;
use App\Models\ToolUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\ActivityLogger; // tambahkan helper

class LoanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tool_id' => 'required|exists:tools,id',
            'unit_code' => 'required|exists:tool_units,code',
            'loan_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:loan_date',
            'purpose' => 'required|string',
        ]);

        $unit = ToolUnit::where('code', $request->unit_code)->where('status', 'available')->first();
        if (!$unit) {
            return back()->withErrors(['unit_code' => 'Unit alat tidak tersedia.']);
        }

        DB::beginTransaction();
        try {
            $loan = Loan::create([
                'user_id' => Auth::id(),
                'tool_id' => $request->tool_id,
                'unit_code' => $request->unit_code,
                'status' => 'pending',
                'loan_date' => $request->loan_date,
                'due_date' => $request->due_date,
                'purpose' => $request->purpose,
                'notes' => $request->notes,
            ]);

            // Catat log
            ActivityLogger::log('create_loan', 'loan', 'Mengajukan peminjaman alat', [
                'loan_id' => $loan->id,
                'tool' => $loan->tool->name,
                'unit' => $loan->unit_code
            ]);

            DB::commit();
            return redirect()->route('peminjam.loans.index')->with('success', 'Pengajuan peminjaman berhasil dikirim.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan pengajuan.']);
        }
    }

    public function index()
    {
        $loans = Loan::where('user_id', Auth::id())->with('tool', 'unit')->orderBy('created_at', 'desc')->paginate(10);
        return view('peminjam.loans.index', compact('loans'));
    }

    public function edit(Loan $loan)
    {
        if ($loan->user_id !== Auth::id() || $loan->status !== 'pending') {
            abort(403, 'Tidak dapat mengedit pengajuan ini.');
        }
        $tool = $loan->tool;
        $availableUnits = $tool->units()->where('status', 'available')->get();
        return view('peminjam.loans.edit', compact('loan', 'tool', 'availableUnits'));
    }

    public function update(Request $request, Loan $loan)
    {
        if ($loan->user_id !== Auth::id() || $loan->status !== 'pending') {
            abort(403);
        }

        $request->validate([
            'unit_code' => 'required|exists:tool_units,code',
            'loan_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:loan_date',
            'purpose' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $unit = ToolUnit::where('code', $request->unit_code)->where('status', 'available')->first();
        if (!$unit) {
            return back()->withErrors(['unit_code' => 'Unit tidak tersedia']);
        }

        $loan->update([
            'unit_code' => $request->unit_code,
            'loan_date' => $request->loan_date,
            'due_date' => $request->due_date,
            'purpose' => $request->purpose,
            'notes' => $request->notes,
        ]);

        // Catat log update
        ActivityLogger::log('update_loan', 'loan', 'Memperbarui pengajuan peminjaman', [
            'loan_id' => $loan->id,
            'changes' => $request->only(['unit_code', 'loan_date', 'due_date', 'purpose'])
        ]);

        return redirect()->route('peminjam.loans.index')->with('success', 'Pengajuan berhasil diperbarui.');
    }

    public function destroy(Loan $loan)
    {
        if ($loan->user_id !== Auth::id() || $loan->status !== 'pending') {
            abort(403);
        }
        
        // Catat log sebelum hapus
        ActivityLogger::log('delete_loan', 'loan', 'Membatalkan pengajuan peminjaman', [
            'loan_id' => $loan->id,
            'tool' => $loan->tool->name
        ]);

        $loan->delete();
        return redirect()->route('peminjam.loans.index')->with('success', 'Pengajuan dibatalkan.');
    }
}