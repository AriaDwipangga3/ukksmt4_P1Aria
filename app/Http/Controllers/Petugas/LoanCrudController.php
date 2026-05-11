<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Tool;
use App\Models\ToolUnit;
use App\Models\User;
use App\Helpers\ActivityLogger;

class LoanCrudController extends Controller
{
    // Menampilkan daftar peminjaman
    public function index()
    {
        $loans = Loan::with('user', 'tool', 'unit')->latest()->paginate(15);
        return view('petugas.loans_crud.index', compact('loans'));
    }

    // Form tambah peminjaman
    public function create()
    {
        $tools = Tool::all(); // ambil semua alat
        $peminjam = User::where('role', 'peminjam')->get();
        return view('petugas.loans_crud.create', compact('tools', 'peminjam'));
    }

    // Simpan peminjaman baru
    public function store(Request $request)
{
    $request->validate([
        'user_id'   => 'required|exists:users,id',
        'tool_id'   => 'required|exists:tools,id',
        'unit_code' => 'required|exists:tool_units,code',
        'loan_date' => 'required|date',
        'due_date'  => 'required|date|after:loan_date',
        'purpose'   => 'required|string',
        'notes'     => 'nullable|string',
    ]);

    $unit = ToolUnit::where('code', $request->unit_code)->where('status', 'available')->first();
    if (!$unit) {
        return back()->withErrors(['unit_code' => 'Unit tidak tersedia.'])->withInput();
    }

    $loan = Loan::create([
        'user_id'     => $request->user_id,
        'tool_id'     => $request->tool_id,
        'unit_code'   => $request->unit_code,
        'employee_id' => auth()->id(),
        'status'      => 'borrowed',   // ← koma ditambahkan
        'loan_date'   => $request->loan_date,
        'due_date'    => $request->due_date,
        'purpose'     => $request->purpose,
        'notes'       => $request->notes,
    ]);

    $unit->update(['status' => 'borrowed']);

    ActivityLogger::log('create_loan_crud', 'loan', 'Petugas menambah peminjaman', ['loan_id' => $loan->id]);

    return redirect()->route('petugas.loans_crud.index')->with('success', 'Peminjaman berhasil ditambahkan.');
}
    // Form edit peminjaman
    public function edit(Loan $loans_crud)
{
    $loan = $loans_crud;
    $tools = Tool::all();
    $peminjam = User::where('role', 'peminjam')->get();
    $units = ToolUnit::where('tool_id', $loan->tool_id)->get();
    return view('petugas.loans_crud.edit', compact('loan', 'tools', 'peminjam', 'units'));
}

    // Update peminjaman
    public function update(Request $request, Loan $loans_crud)
    {
        $loan = $loans_crud;

        $request->validate([
            'user_id'   => 'required|exists:users,id',
            'tool_id'   => 'required|exists:tools,id',
            'unit_code' => 'required|exists:tool_units,code',
            'status'    => 'required|in:pending,approved,rejected,borrowed,returned',
            'loan_date' => 'required|date',
            'due_date'  => 'required|date|after:loan_date',
            'purpose'   => 'required|string',
            'notes'     => 'nullable|string',
        ]);

        // Jika unit diubah, update status unit lama dan baru
        if ($loan->unit_code != $request->unit_code) {
            ToolUnit::where('code', $loan->unit_code)->update(['status' => 'available']);
            $newUnit = ToolUnit::where('code', $request->unit_code)->first();
            if ($newUnit && $newUnit->status == 'available') {
                $newUnit->update(['status' => 'borrowed']);
            } else {
                return back()->withErrors(['unit_code' => 'Unit baru tidak tersedia.']);
            }
        }

        $loan->update([
            'user_id'   => $request->user_id,
            'tool_id'   => $request->tool_id,
            'unit_code' => $request->unit_code,
            'status'    => $request->status,
            'loan_date' => $request->loan_date,
            'due_date'  => $request->due_date,
            'purpose'   => $request->purpose,
            'notes'     => $request->notes,
        ]);

        ActivityLogger::log('update_loan_crud', 'loan', 'Petugas mengupdate peminjaman', ['loan_id' => $loan->id]);

        return redirect()->route('petugas.loans_crud.index')->with('success', 'Peminjaman diperbarui.');
    }

    // Hapus peminjaman
    public function destroy(Loan $loans_crud)
    {
        $loan = $loans_crud;

        // Jika statusnya borrowed/approved, kembalikan status unit menjadi available
        if (in_array($loan->status, ['approved', 'borrowed'])) {
            ToolUnit::where('code', $loan->unit_code)->update(['status' => 'available']);
        }

        $loan->delete();

        ActivityLogger::log('delete_loan_crud', 'loan', 'Petugas menghapus peminjaman', ['loan_id' => $loan->id]);

        return redirect()->route('petugas.loans_crud.index')->with('success', 'Peminjaman dihapus.');
    }
}