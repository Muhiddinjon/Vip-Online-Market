<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title_uz');
            $table->string('title_en');
            $table->string('title_tr');
            $table->text('body_uz');
            $table->text('body_en');
            $table->text('body_tr');
            $table->string('image')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->unsignedInteger('recipients_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_notifications');
    }
};
