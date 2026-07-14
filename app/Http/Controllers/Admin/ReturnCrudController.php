<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssetReturn;
use App\Models\Loan;
use App\Models\ToolUnit;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;

class ReturnCrudController extends Controller
{
    public function index()
    {
        $returns = AssetReturn::with('loan.user', 'loan.tool')->latest()->paginate(15);
        return view('admin.returns_crud.index', compact('returns'));
    }

    public function edit(AssetReturn $returns_crud)
    {
        $return = $returns_crud;
        $return->load('loan.user', 'loan.tool');
        return view('admin.returns_crud.edit', compact('return'));
    }

    public function update(Request $request, AssetReturn $returns_crud)
    {
        $request->validate([
            'condition' => 'required|in:good,damaged,lost',
            'fine'      => 'required|numeric|min:0',
            'notes'     => 'nullable|string',
            'status'    => 'required|in:pending,processed',
        ]);

        $return = $returns_crud;
        $oldStatus = $return->status;
        $return->update([
            'condition'    => $request->condition,
            'fine'         => $request->fine,
            'notes'        => $request->notes,
            'status'       => $request->status,
            'processed_by' => ($request->status == 'processed') ? auth()->id() : $return->processed_by,
        ]);

        if ($request->status == 'processed' && $oldStatus != 'processed') {
            $loan = $return->loan;
            if ($loan && $loan->status != 'returned') {
                $loan->update(['status' => 'returned']);
                if ($loan->unit_code) {
                    ToolUnit::where('code', $loan->unit_code)->update(['status' => 'available']);
                }
            }
        }

        ActivityLogger::log('update_return_admin', 'return', 'Admin mengupdate pengembalian', ['return_id' => $return->id]);
        return redirect()->route('admin.returns_crud.index')->with('success', 'Data pengembalian berhasil diperbarui.');
    }

    public function destroy(AssetReturn $returns_crud)
    {
        if ($returns_crud->status == 'processed') {
            return back()->with('error', 'Tidak dapat menghapus pengembalian yang sudah diproses.');
        }
        $returns_crud->delete();
        ActivityLogger::log('delete_return_admin', 'return', 'Admin menghapus pengembalian', ['return_id' => $returns_crud->id]);
        return redirect()->route('admin.returns_crud.index')->with('success', 'Data pengembalian dihapus.');
    }
}