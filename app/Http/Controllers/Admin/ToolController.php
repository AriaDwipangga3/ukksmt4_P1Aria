<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ToolController extends Controller
{
    public function index()
    {
        $tools = Tool::with('category')->latest()->paginate(10);
        return view('admin.tools.index', compact('tools'));
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
            'item_type'         => 'required|in:barang,bundle',
            'price'             => 'required|numeric|min:0',
            'min_credit_score'  => 'required|integer|min:0',
            'description'       => 'nullable|string',
            'photo'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('photo');
        $data['code_slug'] = Str::slug($request->name) . '-' . time();

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('tools', 'public');
            $data['photo_path'] = $path;
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
            'item_type'         => 'required|in:barang,bundle',
            'price'             => 'required|numeric|min:0',
            'min_credit_score'  => 'required|integer|min:0',
            'description'       => 'nullable|string',
            'photo'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('photo');

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($tool->photo_path && \Storage::disk('public')->exists($tool->photo_path)) {
                \Storage::disk('public')->delete($tool->photo_path);
            }
            $path = $request->file('photo')->store('tools', 'public');
            $data['photo_path'] = $path;
        }

        $tool->update($data);

        return redirect()->route('admin.tools.index')->with('success', 'Alat berhasil diperbarui.');
    }

    public function destroy(Tool $tool)
    {
        if ($tool->photo_path && \Storage::disk('public')->exists($tool->photo_path)) {
            \Storage::disk('public')->delete($tool->photo_path);
        }
        $tool->delete();
        return redirect()->route('admin.tools.index')->with('success', 'Alat berhasil dihapus.');
    }
}