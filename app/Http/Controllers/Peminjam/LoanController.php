<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Tool;
use App\Models\ToolUnit;
use App\Models\Violation;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['tool', 'unit'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        return view('peminjam.loans.index', compact('loans'));
    }

    private function checkCanBorrow(): ?string
    {
        $user = Auth::user();

        if ($user->is_restricted ?? false) {
            return 'Akun Anda dibatasi karena pelanggaran yang belum diselesaikan.';
        }

        $hasUnpaidViolation = Violation::where('user_id', $user->id)
            ->where('status', 'unpaid')
            ->exists();
        if ($hasUnpaidViolation) {
            return 'Anda masih memiliki denda yang belum dibayar. Selesaikan dulu sebelum meminjam.';
        }

        $hasActiveLoan = Loan::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'borrowed'])
            ->exists();
        if ($hasActiveLoan) {
            return 'Anda masih memiliki peminjaman yang sedang berjalan. Kembalikan dulu sebelum meminjam lagi.';
        }

        return null;
    }

    public function create(Tool $tool)
    {
        $blockReason = $this->checkCanBorrow();
        if ($blockReason) {
            return redirect()->route('peminjam.tools.index')->with('error', $blockReason);
        }

        $user = Auth::user();
        if ($user->credit_score < $tool->min_credit_score) {
            return redirect()->route('peminjam.tools.show', $tool)
                ->with('error', "Credit score tidak mencukupi. Minimal: {$tool->min_credit_score}");
        }

        $availableUnits = $tool->units()->where('status', 'available')->get();
        if ($availableUnits->isEmpty()) {
            return redirect()->route('peminjam.tools.show', $tool)
                ->with('error', 'Tidak ada unit tersedia saat ini.');
        }

        return view('peminjam.loans.create', compact('tool', 'availableUnits'));
    }

    public function store(Request $request)
    {
        $blockReason = $this->checkCanBorrow();
        if ($blockReason) {
            return redirect()->route('peminjam.tools.index')->with('error', $blockReason);
        }

        $request->validate([
            'tool_id'   => 'required|exists:tools,id',
            'unit_code' => 'required|exists:tool_units,code',
            'loan_date' => 'required|date|after_or_equal:today',
            'due_date'  => 'required|date|after:loan_date',
            'purpose'   => 'required|string|max:500',
        ]);

        $user = Auth::user();
        $tool = Tool::findOrFail($request->tool_id);

        if ($user->credit_score < $tool->min_credit_score) {
            return back()->with('error', "Credit score tidak mencukupi. Minimal: {$tool->min_credit_score}")->withInput();
        }

        $unit = ToolUnit::where('code', $request->unit_code)->firstOrFail();
        if ($unit->status !== 'available') {
            return back()->with('error', 'Unit yang dipilih sudah tidak tersedia.')->withInput();
        }

        $loan = Loan::create([
            'user_id'   => $user->id,
            'tool_id'   => $request->tool_id,
            'unit_code' => $request->unit_code,
            'status'    => 'pending',
            'loan_date' => $request->loan_date,
            'due_date'  => $request->due_date,
            'purpose'   => $request->purpose,
            'notes'     => $request->notes,
        ]);

        ActivityLogger::log('create_loan', 'loan', "Mengajukan peminjaman ID #{$loan->id}");

        return redirect()->route('peminjam.loans.index')
            ->with('success', 'Pengajuan berhasil dikirim, menunggu persetujuan petugas.');
    }

    public function edit(Loan $loan)
    {
        abort_if($loan->user_id !== Auth::id(), 403);
        abort_if($loan->status !== 'pending', 403, 'Hanya peminjaman pending yang bisa diedit.');

        $tool = $loan->tool;
        $availableUnits = $tool->units()
            ->where(function ($q) use ($loan) {
                $q->where('status', 'available')
                  ->orWhere('code', $loan->unit_code);
            })->get();

        return view('peminjam.loans.edit', compact('loan', 'availableUnits'));
    }

    public function update(Request $request, Loan $loan)
    {
        abort_if($loan->user_id !== Auth::id(), 403);
        abort_if($loan->status !== 'pending', 403);

        $request->validate([
            'unit_code' => 'required|exists:tool_units,code',
            'loan_date' => 'required|date|after_or_equal:today',
            'due_date'  => 'required|date|after:loan_date',
            'purpose'   => 'required|string|max:500',
        ]);

        $loan->update([
            'unit_code' => $request->unit_code,
            'loan_date' => $request->loan_date,
            'due_date'  => $request->due_date,
            'purpose'   => $request->purpose,
            'notes'     => $request->notes,
        ]);

        return redirect()->route('peminjam.loans.index')
            ->with('success', 'Peminjaman berhasil diperbarui.');
    }

    public function destroy(Loan $loan)
    {
        abort_if($loan->user_id !== Auth::id(), 403);
        abort_if($loan->status !== 'pending', 403, 'Hanya peminjaman pending yang bisa dibatalkan.');

        // Use 'rejected' status (or 'cancelled' if you have added it to enum)
        $loan->update(['status' => 'rejected']);

        ActivityLogger::log('cancel_loan', 'loan', "Membatalkan peminjaman ID #{$loan->id}");

        return redirect()->route('peminjam.loans.index')
            ->with('success', 'Peminjaman berhasil dibatalkan.');
    }

    // ========== FITUR PELANGGARAN & DENDA ==========
    public function violations()
    {
        $violations = Violation::with(['loan.tool'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('peminjam.violations.index', compact('violations'));
    }

    public function payViolation($id)
    {
        $violation = Violation::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'unpaid')
            ->firstOrFail();

        $violation->update([
            'status'     => 'paid',
            'settled_by' => Auth::id(),
            'settled_at' => now(),
        ]);

        ActivityLogger::log('pay_violation', 'violation', "Melunasi denda ID #{$violation->id}");

        return redirect()->route('peminjam.violations.index')
            ->with('success', 'Denda berhasil dilunasi.');
    }
}