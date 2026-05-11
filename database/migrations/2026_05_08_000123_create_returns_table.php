<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained()->onDelete('cascade');
            $table->date('return_date');
            $table->string('proof_photo')->nullable(); // path foto bukti
            $table->enum('condition', ['good', 'damaged', 'lost'])->default('good');
            $table->text('notes')->nullable();
            $table->bigInteger('fine')->default(0);
            $table->enum('status', ['pending', 'processed'])->default('pending');
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('returns');
    }
};