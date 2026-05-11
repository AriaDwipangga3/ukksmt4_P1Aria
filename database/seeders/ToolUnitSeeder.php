<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tool;
use App\Models\ToolUnit;

class ToolUnitSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua alat
        $tools = Tool::all();
        foreach ($tools as $tool) {
            // Buat 2 unit untuk setiap alat (kode unik)
            for ($i = 1; $i <= 2; $i++) {
                ToolUnit::create([
                    'code' => $tool->code_slug . '-U' . $i,
                    'tool_id' => $tool->id,
                    'status' => 'available',
                ]);
            }
        }
    }
}