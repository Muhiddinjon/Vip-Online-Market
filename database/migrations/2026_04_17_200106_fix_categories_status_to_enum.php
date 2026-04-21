<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("UPDATE categories SET status = '1' WHERE status = '1'"); // ensure string
        DB::statement("ALTER TABLE categories MODIFY COLUMN status ENUM('active','inactive') DEFAULT 'active'");
        DB::statement("UPDATE categories SET status = 'inactive' WHERE status = '0'");
        DB::statement("UPDATE categories SET status = 'active' WHERE status = '1'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE categories MODIFY COLUMN status TINYINT(1) DEFAULT 1");
        DB::statement("UPDATE categories SET status = 1 WHERE status = 'active'");
        DB::statement("UPDATE categories SET status = 0 WHERE status = 'inactive'");
    }
};
