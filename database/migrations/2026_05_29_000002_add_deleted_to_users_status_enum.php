<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('active','blocked','deleted') NOT NULL DEFAULT 'active'");
    }

    public function down(): void
    {
        DB::statement("UPDATE users SET status = 'blocked' WHERE status = 'deleted'");
        DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('active','blocked') NOT NULL DEFAULT 'active'");
    }
};
