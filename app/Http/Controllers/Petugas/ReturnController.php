<?php
namespace App\Http\Controllers\Petugas;
use App\Http\Controllers\Controller;
use App\Models\AssetReturn;
use App\Models\Loan;
use App\Models\ToolUnit;
use App\Models\Violation;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function pending()
    {
        $returns = AssetReturn::with('loan.tool', 'loan.user')->where('status', 'pending')->paginate(10);
        return view('petugas.returns.pending', compact('returns'));
    }

    public function processForm(AssetReturn $return)
    {
        return view('petugas.returns.process', compact('return'));
    }

    public function process(Request $request, AssetReturn $return)
    {
        $request->validate([
            'condition' => 'required|in:good,damaged,lost',
            'fine' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);
        $fine = $request->fine ?? 0;
        DB::beginTransaction();
        try {
            $return->update([
                'condition' => $request->condition,
                'fine' => $fine,
                'notes' => $request->notes,
                'status' => 'processed',
                'processed_by' => Auth::id(),
            ]);
            $loan = $return->loan;
            $loan->update(['status' => 'returned']);
            ToolUnit::where('code', $loan->unit_code)->update(['status' => 'available']);
            // Violation
            $type = match($request->condition) {
                'damaged' => 'damaged',
                'lost' => 'lost',
                default => 'late'
            };
            $violation = Violation::where('loan_id', $loan->id)->first();
            if ($violation) {
                $violation->update(['fine' => $violation->fine + $fine, 'type' => $type]);
            } elseif ($fine > 0 || $request->condition !== 'good') {
                Violation::create([
                    'loan_id' => $loan->id,
                    'user_id' => $loan->user_id,
                    'type' => $type,
                    'fine' => $fine,
                    'status' => 'unpaid',
                    'description' => $request->notes ?? "Denda dari pengembalian",
                ]);
            }
            DB::commit();
            ActivityLogger::log('process_return', 'return', "Proses pengembalian ID {$return->id}");
            return redirect()->route('petugas.returns.pending')->with('success', 'Pengembalian diproses.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}