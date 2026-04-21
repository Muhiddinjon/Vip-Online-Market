<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("UPDATE users SET role = 'admin' WHERE role = 'moderator'");
        DB::statement("ALTER TABLE users MODIFY COLUMN role
            ENUM('admin','restaurant','courier','customer') DEFAULT 'customer'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role
            ENUM('admin','moderator','restaurant','courier','customer') DEFAULT 'customer'");
    }
};
