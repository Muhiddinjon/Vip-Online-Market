<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->time('open_time')->nullable()->after('working_hours');
            $table->time('close_time')->nullable()->after('open_time');
            $table->double('rating', 3, 1)->default(5.0)->after('close_time');
        });
    }

    public function down(): void {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn(['open_time', 'close_time', 'rating']);
        });
    }
};
