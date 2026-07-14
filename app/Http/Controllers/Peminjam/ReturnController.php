<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\AssetReturn;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReturnController extends Controller
{
    public function create(Loan $loan)
    {
        // Hanya loan dengan status 'borrowed' yang bisa dikembalikan
        if ($loan->status !== 'borrowed') {
            return redirect()->route('peminjam.loans.index')
                ->with('error', 'Peminjaman ini tidak dapat dikembalikan.');
        }

        // Cek apakah sudah ada pengajuan return
        if (AssetReturn::where('loan_id', $loan->id)->exists()) {
            return redirect()->route('peminjam.loans.index')
                ->with('error', 'Anda sudah mengajukan pengembalian untuk peminjaman ini.');
        }

        return view('peminjam.returns.create', compact('loan'));
    }

    public function store(Request $request, Loan $loan)
    {
        $request->validate([
            'return_date' => 'required|date',
            'proof_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'notes'       => 'nullable|string|max:500',
        ]);

        $path = $request->file('proof_photo')->store('returns_proof', 'public');

        AssetReturn::create([
            'loan_id'     => $loan->id,
            'return_date' => $request->return_date,
            'proof_photo' => $path,
            'notes'       => $request->notes,
            'status'      => 'pending',
            'fine'        => 0,
            'condition'   => 'good',
        ]);

        ActivityLogger::log('return_request', 'return', "Mengajukan pengembalian untuk loan ID {$loan->id}");

        return redirect()->route('peminjam.returns.index')
            ->with('success', 'Pengajuan pengembalian berhasil dikirim. Petugas akan memproses.');
    }

    public function index()
    {
        $returns = AssetReturn::whereHas('loan', function ($q) {
            $q->where('user_id', Auth::id());
        })->with('loan.tool')->orderBy('created_at', 'desc')->paginate(10);

        return view('peminjam.returns.index', compact('returns'));
    }
}