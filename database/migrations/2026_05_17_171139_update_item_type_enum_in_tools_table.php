<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Ubah kolom menjadi string (varchar) terlebih dahulu
        DB::statement("ALTER TABLE tools MODIFY item_type VARCHAR(20)");
        
        // 2. Update data: ubah 'barang' menjadi 'single'
        DB::statement("UPDATE tools SET item_type = 'single' WHERE item_type = 'barang'");
        
        // 3. Set default 'single' untuk baris baru
        DB::statement("ALTER TABLE tools ALTER COLUMN item_type SET DEFAULT 'single'");
    }

    public function down()
    {
        // Rollback: ubah kembali menjadi string dan konversi 'single' menjadi 'barang'
        DB::statement("ALTER TABLE tools MODIFY item_type VARCHAR(20)");
        DB::statement("UPDATE tools SET item_type = 'barang' WHERE item_type = 'single'");
        DB::statement("ALTER TABLE tools ALTER COLUMN item_type SET DEFAULT 'barang'");
    }
};