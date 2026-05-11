<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use App\Models\Loan;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViolationController extends Controller
{
    public function index(Request $request)
    {
        $violations = Violation::with(['user', 'loan.tool'])
            ->when($request->search, function($q) use ($request) {
                $q->whereHas('user', function($q2) use ($request) {
                    $q2->where('name', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->latest()
            ->paginate(15);

        return view('petugas.violations.index', compact('violations'));
    }

    public function create()
    {
        // Ambil loan yang sudah dikembalikan tapi belum punya pelanggaran (opsional)
        $loans = Loan::with(['user', 'tool'])
            ->where('status', 'returned')
            ->whereDoesntHave('violation')
            ->get();
        return view('petugas.violations.create', compact('loans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'loan_id'     => 'required|exists:loans,id',
            'type'        => 'required|in:late,damaged,lost',
            'fine'        => 'required|numeric|min:0',
            'score'       => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $loan = Loan::findOrFail($request->loan_id);

        $violation = Violation::create([
            'loan_id'     => $loan->id,
            'user_id'     => $loan->user_id,
            'type'        => $request->type,
            'fine'        => $request->fine,
            'score'       => $request->score ?? 0,
            'description' => $request->description,
            'status'      => 'unpaid',
        ]);

        ActivityLogger::log('create_violation', 'violation', 'Mencatat pelanggaran baru', [
            'violation_id' => $violation->id,
            'user_id' => $loan->user_id
        ]);

        return redirect()->route('petugas.violations.index')
            ->with('success', 'Pelanggaran berhasil dicatat.');
    }

    public function edit(Violation $violation)
    {
        $violation->load('loan.tool', 'user');
        return view('petugas.violations.edit', compact('violation'));
    }

    public function update(Request $request, Violation $violation)
    {
        $request->validate([
            'type'        => 'required|in:late,damaged,lost',
            'fine'        => 'required|numeric|min:0',
            'score'       => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'status'      => 'required|in:unpaid,paid',
        ]);

        $oldStatus = $violation->status;

        $violation->update([
            'type'        => $request->type,
            'fine'        => $request->fine,
            'score'       => $request->score ?? 0,
            'description' => $request->description,
            'status'      => $request->status,
            'settled_by'  => ($request->status == 'paid' && $oldStatus == 'unpaid') ? Auth::id() : $violation->settled_by,
            'settled_at'  => ($request->status == 'paid' && $oldStatus == 'unpaid') ? now() : $violation->settled_at,
        ]);

        ActivityLogger::log('update_violation', 'violation', "Mengupdate pelanggaran ID #{$violation->id}");

        return redirect()->route('petugas.violations.index')
            ->with('success', 'Pelanggaran berhasil diperbarui.');
    }

    public function destroy(Violation $violation)
    {
        $violation->delete();
        ActivityLogger::log('delete_violation', 'violation', "Menghapus pelanggaran ID #{$violation->id}");
        return redirect()->route('petugas.violations.index')
            ->with('success', 'Pelanggaran dihapus.');
    }
}