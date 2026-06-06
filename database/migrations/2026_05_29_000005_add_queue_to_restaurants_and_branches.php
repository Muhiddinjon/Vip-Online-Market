<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->unsignedInteger('queue')->default(0)->after('rating');
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->unsignedInteger('queue')->default(0)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn('queue');
        });
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn('queue');
        });
    }
};
