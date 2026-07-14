<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tool_units', function (Blueprint $table) {
            $table->string('code')->primary();  // Kode unik unit (misal: LAP-001)
            $table->foreignId('tool_id')
                  ->constrained('tools')
                  ->onDelete('cascade');       // Relasi ke tabel tools
            $table->enum('status', [
                'available',   // Tersedia
                'borrowed',    // Sedang dipinjam
                'damaged',     // Rusak
                'maintenance'  // Dalam perawatan
            ])->default('available');
            $table->text('notes')->nullable();  // Catatan tambahan (kerusakan, dll)
            $table->timestamps();               // created_at, updated_at

            // Index untuk mempercepat pencarian berdasarkan tool_id
            $table->index('tool_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tool_units');
    }
};