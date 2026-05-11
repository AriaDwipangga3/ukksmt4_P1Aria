<?php
namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\AssetReturn;
use App\Models\Loan;
use App\Models\ToolUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function pending()
    {
        $returns = AssetReturn::with('loan.user', 'loan.tool')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->paginate(10);
        return view('petugas.returns.pending', compact('returns'));
    }

    public function processForm(AssetReturn $return)
    {
        if ($return->status !== 'pending') {
            return redirect()->route('petugas.returns.pending')->with('error', 'Sudah diproses.');
        }
        return view('petugas.returns.process', compact('return'));
    }

    public function process(Request $request, AssetReturn $return)
    {
        $request->validate([
            'condition' => 'required|in:good,damaged,lost',
            'fine' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $return->update([
                'condition' => $request->condition,
                'fine' => $request->fine ?? 0,
                'notes' => $request->notes,
                'status' => 'processed',
                'processed_by' => Auth::id(),
            ]);
            // Update loan status -> returned
            $loan = $return->loan;
            $loan->update(['status' => 'returned']);
            // Update unit status -> available
            ToolUnit::where('code', $loan->unit_code)->update(['status' => 'available']);
            DB::commit();
            return redirect()->route('petugas.returns.pending')->with('success', 'Pengembalian diproses.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: '.$e->getMessage());
        }
    }
}