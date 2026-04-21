<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->softDeletes()->after('sort_order');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->softDeletes()->after('unit');
        });
    }

    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
