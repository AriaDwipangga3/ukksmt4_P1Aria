<?php
namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\AssetReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ActivityLogger;

class ReturnController extends Controller
{
    public function create(Loan $loan)
    {
        if ($loan->status !== 'borrowed') {
            return redirect()->route('peminjam.loans.index')->with('error', 'Peminjaman ini tidak dapat dikembalikan.');
        }
        if (AssetReturn::where('loan_id', $loan->id)->exists()) {
            return redirect()->route('peminjam.loans.index')->with('error', 'Anda sudah mengajukan pengembalian untuk peminjaman ini.');
        }
        return view('peminjam.returns.create', compact('loan'));
    }

    public function store(Request $request, Loan $loan)
    {
        $request->validate([
            'return_date' => 'required|date',
            'proof_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'notes' => 'nullable|string',
        ]);

        $path = $request->file('proof_photo')->store('returns_proof', 'public');

        $return = AssetReturn::create([
            'loan_id' => $loan->id,
            'return_date' => $request->return_date,
            'proof_photo' => $path,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        // Catat log
        ActivityLogger::log('return_request', 'return', 'Mengajukan pengembalian alat', [
            'return_id' => $return->id,
            'loan_id' => $loan->id,
            'tool' => $loan->tool->name
        ]);

        return redirect()->route('peminjam.returns.index')->with('success', 'Pengajuan pengembalian berhasil dikirim. Petugas akan memproses.');
    }

    public function index()
    {
        $returns = AssetReturn::whereHas('loan', function ($q) {
            $q->where('user_id', Auth::id());
        })->with('loan.tool')->orderBy('created_at', 'desc')->paginate(10);
        return view('peminjam.returns.index', compact('returns'));
    }
}