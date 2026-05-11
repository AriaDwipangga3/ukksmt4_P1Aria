<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\AssetReturn;
use App\Models\Violation;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Laporan Peminjaman
        $loans = Loan::with(['user', 'tool'])
            ->when($request->loan_date_from, function($q) use ($request) {
                $q->whereDate('loan_date', '>=', $request->loan_date_from);
            })
            ->when($request->loan_date_to, function($q) use ($request) {
                $q->whereDate('loan_date', '<=', $request->loan_date_to);
            })
            ->when($request->loan_status, fn($q) => $q->where('status', $request->loan_status))
            ->orderBy('loan_date', 'desc')
            ->get();

        // Laporan Pengembalian
        $returns = AssetReturn::with(['loan.user', 'loan.tool'])
            ->when($request->return_date_from, function($q) use ($request) {
                $q->whereDate('return_date', '>=', $request->return_date_from);
            })
            ->when($request->return_date_to, function($q) use ($request) {
                $q->whereDate('return_date', '<=', $request->return_date_to);
            })
            ->when($request->return_condition, fn($q) => $q->where('condition', $request->return_condition))
            ->orderBy('return_date', 'desc')
            ->get();

        // Laporan Pelanggaran
        $violations = Violation::with(['user', 'loan.tool'])
            ->when($request->violation_date_from, function($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->violation_date_from);
            })
            ->when($request->violation_date_to, function($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->violation_date_to);
            })
            ->when($request->violation_type, fn($q) => $q->where('type', $request->violation_type))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('petugas.reports.index', compact('loans', 'returns', 'violations'));
    }
}