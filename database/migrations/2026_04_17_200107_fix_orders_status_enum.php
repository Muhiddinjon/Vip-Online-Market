<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("UPDATE orders SET status = 'confirmed' WHERE status = 'accepted'");
        DB::statement("UPDATE orders SET status = 'delivering' WHERE status = 'picked_up'");
        DB::statement("ALTER TABLE orders MODIFY COLUMN status
            ENUM('pending','confirmed','preparing','ready','delivering','delivered','cancelled')
            DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status
            ENUM('pending','accepted','preparing','ready','picked_up','delivered','cancelled')
            DEFAULT 'pending'");
        DB::statement("UPDATE orders SET status = 'accepted' WHERE status = 'confirmed'");
        DB::statement("UPDATE orders SET status = 'picked_up' WHERE status = 'delivering'");
    }
};
