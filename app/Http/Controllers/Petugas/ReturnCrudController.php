<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\ReturnRecord;
use App\Models\ToolUnit;
use App\Models\Loan;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;

class ReturnCrudController extends Controller
{
    public function index(Request $request)
    {
        $returns = ReturnRecord::with(['loan.user', 'loan.tool'])
                    ->when($request->search, function($q) use ($request) {
                        $q->whereHas('loan.user', function($q2) use ($request) {
                            $q2->where('name', 'like', '%' . $request->search . '%');
                        })->orWhereHas('loan.tool', function($q2) use ($request) {
                            $q2->where('name', 'like', '%' . $request->search . '%');
                        });
                    })
                    ->when($request->status, fn($q) => $q->where('status', $request->status))
                    ->latest()
                    ->paginate(15);

        return view('petugas.returns_crud.index', compact('returns'));
    }

    public function edit(ReturnRecord $returns_crud)
    {
        $return = $returns_crud;
        $return->load('loan.user', 'loan.tool');
        return view('petugas.returns_crud.edit', compact('return'));
    }

    public function update(Request $request, ReturnRecord $returns_crud)
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
            'processed_by' => ($request->status == 'processed') ? auth()->id() : null,
        ]);

        // Jika status berubah menjadi processed, update loan dan unit
        if ($request->status == 'processed' && $oldStatus != 'processed') {
            $loan = $return->loan;
            if ($loan && $loan->status != 'returned') {
                $loan->update(['status' => 'returned']);
                if ($loan->unit_code) {
                    ToolUnit::where('code', $loan->unit_code)->update(['status' => 'available']);
                }
            }
        }

        ActivityLogger::log('update_return_crud', 'return', "Update pengembalian ID #{$return->id}");

        return redirect()->route('petugas.returns_crud.index')
                ->with('success', 'Data pengembalian berhasil diperbarui.');
    }

    public function destroy(ReturnRecord $returns_crud)
    {
        if ($returns_crud->status == 'processed') {
            return back()->with('error', 'Tidak bisa menghapus pengembalian yang sudah diproses.');
        }

        ActivityLogger::log('delete_return_crud', 'return', "Hapus pengembalian ID #{$returns_crud->id}");
        $returns_crud->delete();

        return redirect()->route('petugas.returns_crud.index')
                ->with('success', 'Data pengembalian dihapus.');
    }
}