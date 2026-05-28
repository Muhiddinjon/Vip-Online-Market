<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->unsignedSmallInteger('delivery_time')->nullable()->comment('minutes');
            $table->decimal('delivery_fee', 10, 2)->nullable();
        });
    }

    public function down(): void {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn(['delivery_time', 'delivery_fee']);
        });
    }
};
