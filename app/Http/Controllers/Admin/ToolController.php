<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ToolController extends Controller
{
public function index(Request $request)
{
    $query = Tool::with('category');

    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }
    if ($request->filled('item_type')) {
        $query->where('item_type', $request->item_type);
    }

    $tools = $query->latest()->paginate(10);
    $categories = Category::all();

    return view('admin.tools.index', compact('tools', 'categories'));
}

    public function create()
    {
        $categories = Category::all();
        return view('admin.tools.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'category_id'       => 'required|exists:categories,id',
            'item_type'         => 'required|in:single,bundle,bundle_tool',
            'price'             => 'required|numeric|min:0',
            'min_credit_score'  => 'nullable|integer|min:0',
            'description'       => 'nullable|string',
            'photo'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('photo');
        $data['code_slug'] = Str::slug($request->name) . '-' . time();

        if ($request->hasFile('photo')) {
            try {
                $file = $request->file('photo');
                if (!$file->isValid()) {
                    throw new \Exception('File tidak valid atau rusak.');
                }
                $path = $file->store('tools', 'public');
                if (!$path) {
                    throw new \Exception('Gagal menyimpan file ke storage.');
                }
                $data['photo_path'] = $path;
                Log::info('Upload foto berhasil: ' . $path);
            } catch (\Exception $e) {
                Log::error('Upload foto error: ' . $e->getMessage());
                return back()->withErrors(['photo' => 'Gagal mengupload foto: ' . $e->getMessage()])->withInput();
            }
        }

        Tool::create($data);
        return redirect()->route('admin.tools.index')->with('success', 'Alat berhasil ditambahkan.');
    }

    public function edit(Tool $tool)
    {
        $categories = Category::all();
        return view('admin.tools.edit', compact('tool', 'categories'));
    }

    public function update(Request $request, Tool $tool)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'category_id'       => 'required|exists:categories,id',
            'item_type'         => 'required|in:single,bundle,bundle_tool',
            'price'             => 'required|numeric|min:0',
            'min_credit_score'  => 'nullable|integer|min:0',
            'description'       => 'nullable|string',
            'photo'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('photo');

        if ($request->hasFile('photo')) {
            try {
                $file = $request->file('photo');
                if (!$file->isValid()) {
                    throw new \Exception('File tidak valid atau rusak.');
                }
                // Hapus foto lama jika ada
                if ($tool->photo_path && Storage::disk('public')->exists($tool->photo_path)) {
                    Storage::disk('public')->delete($tool->photo_path);
                    Log::info('Foto lama dihapus: ' . $tool->photo_path);
                }
                $path = $file->store('tools', 'public');
                if (!$path) {
                    throw new \Exception('Gagal menyimpan file ke storage.');
                }
                $data['photo_path'] = $path;
                Log::info('Upload foto baru berhasil: ' . $path);
            } catch (\Exception $e) {
                Log::error('Upload foto error: ' . $e->getMessage());
                return back()->withErrors(['photo' => 'Gagal mengupload foto: ' . $e->getMessage()])->withInput();
            }
        }

        $tool->update($data);
        return redirect()->route('admin.tools.index')->with('success', 'Alat berhasil diperbarui.');
    }

    public function units()
{
    return $this->hasMany(ToolUnit::class);
}

    public function destroy(Tool $tool)
    {
        if ($tool->photo_path && Storage::disk('public')->exists($tool->photo_path)) {
            Storage::disk('public')->delete($tool->photo_path);
        }
        $tool->delete();
        return redirect()->route('admin.tools.index')->with('success', 'Alat berhasil dihapus.');
    }
}