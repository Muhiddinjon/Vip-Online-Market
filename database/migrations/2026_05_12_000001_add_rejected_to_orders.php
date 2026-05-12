<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Requires DB admin privileges. Run manually if vip_user lacks ALTER:
        // ALTER TABLE orders MODIFY COLUMN status
        //   ENUM('pending','confirmed','rejected','preparing','ready','delivering','delivered','cancelled')
        //   DEFAULT 'pending';
        // ALTER TABLE orders ADD COLUMN reject_reason TEXT NULL AFTER note;
        try {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status
                ENUM('pending','confirmed','rejected','preparing','ready','delivering','delivered','cancelled')
                DEFAULT 'pending'");
        } catch (\Exception $e) {
            // Skipped: run SQL above with root credentials
        }

        try {
            if (! Schema::hasColumn('orders', 'reject_reason')) {
                Schema::table('orders', function (Blueprint $table) {
                    $table->text('reject_reason')->nullable()->after('note');
                });
            }
        } catch (\Exception $e) {
            // Skipped: run SQL above with root credentials
        }
    }

    public function down(): void
    {
        try {
            if (Schema::hasColumn('orders', 'reject_reason')) {
                Schema::table('orders', function (Blueprint $table) {
                    $table->dropColumn('reject_reason');
                });
            }
        } catch (\Exception $e) {}

        try {
            DB::statement("UPDATE orders SET status = 'cancelled' WHERE status = 'rejected'");
            DB::statement("ALTER TABLE orders MODIFY COLUMN status
                ENUM('pending','confirmed','preparing','ready','delivering','delivered','cancelled')
                DEFAULT 'pending'");
        } catch (\Exception $e) {}
    }
};
