<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->string('keyword', 100)->unique();
            $table->text('value')->nullable();
            $table->enum('type', ['text', 'textarea', 'switch'])->default('text');
            $table->tinyInteger('platform')->unsigned()->default(0)
                ->comment('1 = client API ga uzatiladi');
            $table->timestamps();
        });

        DB::table('configs')->insert([
            [
                'title'    => json_encode(['uz' => 'Call-center raqamlari', 'en' => 'Call-center numbers', 'tr' => 'Call-center numaraları']),
                'keyword'  => 'call_center_numbers',
                'value'    => '+998908832323;+998918832323',
                'type'     => 'textarea',
                'platform' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title'    => json_encode(['uz' => 'Minimal buyurtma narxi (so\'m)', 'en' => 'Minimum order amount (UZS)', 'tr' => 'Minimum sipariş tutarı (UZS)']),
                'keyword'  => 'min_order_amount',
                'value'    => '30000',
                'type'     => 'text',
                'platform' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title'    => json_encode(['uz' => 'Maksimal buyurtma narxi (so\'m)', 'en' => 'Maximum order amount (UZS)', 'tr' => 'Maksimum sipariş tutarı (UZS)']),
                'keyword'  => 'max_order_amount',
                'value'    => '1000000',
                'type'     => 'text',
                'platform' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('configs');
    }
};
