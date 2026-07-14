<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use App\Models\Loan;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ViolationController extends Controller
{
    public function index(Request $request)
    {
        $violations = Violation::with(['user', 'loan.tool'])
            ->when($request->search, fn($q) =>
                $q->whereHas('user', fn($q2) =>
                    $q2->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like', '%' . $request->search . '%')
                )
            )
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->type,   fn($q) => $q->where('type',   $request->type))
            ->latest()
            ->paginate(15);

        return view('petugas.violations.index', compact('violations'));
    }

    public function create()
    {
        // Ambil loan berstatus returned yang belum punya violation
        // Gunakan whereNotIn agar tidak butuh relasi violations() di model Loan
        $loanIdsWithViolation = Violation::pluck('loan_id')->unique()->toArray();

        $loans = Loan::with(['user', 'tool'])
            ->where('status', 'returned')
            ->whereNotIn('id', $loanIdsWithViolation)
            ->get();

        return view('petugas.violations.create', compact('loans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'loan_id'     => 'required|exists:loans,id',
            'type'        => 'required|in:late,damaged,lost',
            'fine'        => 'required|numeric|min:0',
            'total_score' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $loan = Loan::findOrFail($request->loan_id);

        $violation = Violation::create([
            'loan_id'     => $loan->id,
            'user_id'     => $loan->user_id,
            'type'        => $request->type,
            'fine'        => $request->fine,
            'total_score' => $request->total_score ?? 0,
            'description' => $request->description,
            'status'      => 'unpaid',
        ]);

        ActivityLog::record(
            'create_violation', 'violation',
            "Mencatat pelanggaran baru ID #{$violation->id} untuk user ID {$loan->user_id}"
        );

        return redirect()->route('petugas.violations.index')
            ->with('success', 'Pelanggaran berhasil dicatat.');
    }

    public function edit(Violation $violation)
    {
        $violation->load(['loan.tool', 'user']);
        return view('petugas.violations.edit', compact('violation'));
    }

    public function update(Request $request, Violation $violation)
    {
        $request->validate([
            'type'        => 'required|in:late,damaged,lost',
            'fine'        => 'required|numeric|min:0',
            'total_score' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'status'      => 'required|in:unpaid,paid,settled',
        ]);

        $violation->update([
            'type'        => $request->type,
            'fine'        => $request->fine,
            'total_score' => $request->total_score ?? $violation->total_score,
            'description' => $request->description,
            'status'      => $request->status,
        ]);

        ActivityLog::record(
            'update_violation', 'violation',
            "Mengupdate pelanggaran ID #{$violation->id}"
        );

        return redirect()->route('petugas.violations.index')
            ->with('success', 'Pelanggaran berhasil diperbarui.');
    }

    public function destroy(Violation $violation)
    {
        ActivityLog::record(
            'delete_violation', 'violation',
            "Menghapus pelanggaran ID #{$violation->id}"
        );

        $violation->delete();

        return redirect()->route('petugas.violations.index')
            ->with('success', 'Pelanggaran berhasil dihapus.');
    }
}