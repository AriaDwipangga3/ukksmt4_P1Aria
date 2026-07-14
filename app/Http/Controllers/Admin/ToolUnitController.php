<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\ToolUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ToolUnitController extends Controller
{
    /**
     * Daftar unit dari suatu alat.
     */
    public function index(Tool $tool)
    {
        $units = $tool->units()->latest()->paginate(15);
        return view('admin.tools.units.index', compact('tool', 'units'));
    }

    /**
     * Form tambah unit baru.
     */
    public function create(Tool $tool)
    {
        return view('admin.tools.units.create', compact('tool'));
    }

    /**
     * Generate kode unit otomatis.
     *
     * Format: slug-nama-alat-001, -002, dst.
     */
    private function generateUnitCode(Tool $tool): string
    {
        // Prefix diambil dari slug nama alat (misal: "laptop-asus")
        $prefix = Str::slug($tool->name);
        
        // Cari unit terakhir berdasarkan kode (descending)
        $lastUnit = $tool->units()->orderBy('code', 'desc')->first();
        
        if ($lastUnit) {
            // Ambil angka di akhir kode, misal "laptop-asus-005" -> 5
            $lastNumber = (int) substr($lastUnit->code, strrpos($lastUnit->code, '-') + 1);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        // Format angka 3 digit (001, 002, ..., 999)
        $formattedNumber = str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        
        return $prefix . '-' . $formattedNumber;
    }

    /**
     * Simpan unit baru ke database.
     */
    public function store(Request $request, Tool $tool)
    {
        $request->validate([
            'status' => 'required|in:available,borrowed,damaged,maintenance',
            'notes'  => 'nullable|string',
        ]);

        // Buat kode unit secara otomatis
        $code = $this->generateUnitCode($tool);

        $tool->units()->create([
            'code'   => $code,
            'status' => $request->status,
            'notes'  => $request->notes,
        ]);

        return redirect()->route('admin.tools.units.index', $tool)
            ->with('success', "Unit berhasil ditambahkan dengan kode: {$code}");
    }

    /**
     * Form edit unit.
     */
    public function edit(Tool $tool, ToolUnit $unit)
    {
        // Pastikan unit milik alat yang sesuai
        if ($unit->tool_id != $tool->id) {
            abort(404);
        }
        return view('admin.tools.units.edit', compact('tool', 'unit'));
    }

    /**
     * Update data unit.
     */
    public function update(Request $request, Tool $tool, ToolUnit $unit)
    {
        if ($unit->tool_id != $tool->id) {
            abort(404);
        }

        $request->validate([
            'code'   => 'required|string|unique:tool_units,code,' . $unit->code . ',code',
            'status' => 'required|in:available,borrowed,damaged,maintenance',
            'notes'  => 'nullable|string',
        ]);

        $unit->update($request->only('code', 'status', 'notes'));

        return redirect()->route('admin.tools.units.index', $tool)
            ->with('success', 'Unit berhasil diperbarui.');
    }

    /**
     * Hapus unit.
     */
    public function destroy(Tool $tool, ToolUnit $unit)
    {
        if ($unit->tool_id != $tool->id) {
            abort(404);
        }

        // Cegah penghapusan jika unit sedang dipinjam (opsional)
        if ($unit->status == 'borrowed') {
            return back()->with('error', 'Tidak dapat menghapus unit yang sedang dipinjam.');
        }

        $unit->delete();

        return redirect()->route('admin.tools.units.index', $tool)
            ->with('success', 'Unit berhasil dihapus.');
    }
}