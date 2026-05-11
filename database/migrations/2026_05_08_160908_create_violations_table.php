<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['late', 'damaged', 'lost']);
            $table->integer('score')->nullable();
            $table->bigInteger('fine')->default(0);
            $table->text('description')->nullable();
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
            $table->foreignId('settled_by')->nullable()->constrained('users');
            $table->timestamp('settled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('violations');
    }
};