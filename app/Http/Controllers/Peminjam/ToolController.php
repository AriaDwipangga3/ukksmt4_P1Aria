<?php
namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function index()
    {
        $tools = Tool::with(['category', 'units'])->get();
        return view('peminjam.tools.index', compact('tools'));
    }

    public function show(Tool $tool)
    {
        $tool->load(['category', 'units']);
        return view('peminjam.tools.show', compact('tool'));
    }
}