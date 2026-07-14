<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Tool;
use App\Models\ToolUnit;
use App\Models\User;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;

class LoanCrudController extends Controller
{
    public function index()
    {
        $loans = Loan::with('user', 'tool', 'unit')->latest()->paginate(15);
        return view('admin.loans_crud.index', compact('loans'));
    }

    public function create()
    {
        $tools = Tool::all();
        $peminjam = User::where('role', 'peminjam')->get();
        return view('admin.loans_crud.create', compact('tools', 'peminjam'));
    }

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
            'status'      => 'borrowed',
            'loan_date'   => $request->loan_date,
            'due_date'    => $request->due_date,
            'purpose'     => $request->purpose,
            'notes'       => $request->notes,
        ]);

        $unit->update(['status' => 'borrowed']);
        ActivityLogger::log('create_loan_admin', 'loan', 'Admin menambah peminjaman', ['loan_id' => $loan->id]);

        return redirect()->route('admin.loans_crud.index')->with('success', 'Peminjaman berhasil ditambahkan.');
    }

    public function edit(Loan $loans_crud)
    {
        $loan = $loans_crud;
        $tools = Tool::all();
        $peminjam = User::where('role', 'peminjam')->get();
        $units = ToolUnit::where('tool_id', $loan->tool_id)->get();
        return view('admin.loans_crud.edit', compact('loan', 'tools', 'peminjam', 'units'));
    }

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

        if ($loan->unit_code != $request->unit_code) {
            ToolUnit::where('code', $loan->unit_code)->update(['status' => 'available']);
            $newUnit = ToolUnit::where('code', $request->unit_code)->first();
            if ($newUnit && $newUnit->status == 'available') {
                $newUnit->update(['status' => 'borrowed']);
            } else {
                return back()->withErrors(['unit_code' => 'Unit baru tidak tersedia.']);
            }
        }

        $loan->update($request->only(['user_id', 'tool_id', 'unit_code', 'status', 'loan_date', 'due_date', 'purpose', 'notes']));
        ActivityLogger::log('update_loan_admin', 'loan', 'Admin mengupdate peminjaman', ['loan_id' => $loan->id]);
        return redirect()->route('admin.loans_crud.index')->with('success', 'Peminjaman diperbarui.');
    }

    public function destroy(Loan $loans_crud)
    {
        $loan = $loans_crud;
        if (in_array($loan->status, ['approved', 'borrowed'])) {
            ToolUnit::where('code', $loan->unit_code)->update(['status' => 'available']);
        }
        $loan->delete();
        ActivityLogger::log('delete_loan_admin', 'loan', 'Admin menghapus peminjaman', ['loan_id' => $loan->id]);
        return redirect()->route('admin.loans_crud.index')->with('success', 'Peminjaman dihapus.');
    }
}