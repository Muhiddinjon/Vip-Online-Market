<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('orders', 'branch_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->after('restaurant_id')
                    ->constrained()->nullOnDelete();
            });
        }

        DB::statement(
            "ALTER TABLE `orders` MODIFY COLUMN `status`
             ENUM('pending','confirmed','rejected','preparing','ready','delivering','delivered','cancelled')
             DEFAULT 'pending'"
        );

        if (! Schema::hasColumn('orders', 'reject_reason')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->text('reject_reason')->nullable()->after('note');
            });
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });
    }
};
