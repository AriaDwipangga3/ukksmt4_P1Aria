<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tool_id')->constrained();
            $table->string('unit_code');
            $table->foreign('unit_code')->references('code')->on('tool_units')->onDelete('restrict');
            $table->foreignId('employee_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['pending', 'approved', 'rejected', 'borrowed', 'returned'])->default('pending');
            $table->date('loan_date');
            $table->date('due_date');
            $table->text('purpose')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('loans');
    }
};