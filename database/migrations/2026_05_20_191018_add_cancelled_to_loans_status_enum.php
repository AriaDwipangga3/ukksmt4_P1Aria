<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE loans MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'borrowed', 'returned', 'cancelled') DEFAULT 'pending'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE loans MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'borrowed', 'returned') DEFAULT 'pending'");
    }
};