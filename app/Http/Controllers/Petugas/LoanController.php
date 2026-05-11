<?php
namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\ToolUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function pending()
    {
        $loans = Loan::with('user', 'tool', 'unit')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->paginate(10);
        return view('petugas.loans.pending', compact('loans'));
    }

    public function approve(Loan $loan)
    {
        if ($loan->status !== 'pending') {
            return back()->with('error', 'Pengajuan sudah diproses.');
        }
        DB::beginTransaction();
        try {
            $loan->update([
                'status' => 'borrowed',
                'employee_id' => Auth::id(),
            ]);
            // Update unit status menjadi borrowed
            $unit = ToolUnit::where('code', $loan->unit_code)->first();
            if ($unit) $unit->update(['status' => 'borrowed']);
            DB::commit();
            return redirect()->route('petugas.loans.pending')->with('success', 'Peminjaman disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyetujui: '.$e->getMessage());
        }
    }

    public function reject(Loan $loan)
    {
        if ($loan->status !== 'pending') {
            return back()->with('error', 'Pengajuan sudah diproses.');
        }
        $loan->update(['status' => 'rejected', 'employee_id' => Auth::id()]);
        return redirect()->route('petugas.loans.pending')->with('success', 'Pengajuan ditolak.');
    }

    
}