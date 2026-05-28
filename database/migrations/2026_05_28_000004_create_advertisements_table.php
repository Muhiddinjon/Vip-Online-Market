<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image_path')->nullable();
            $table->text('details')->nullable();
            $table->string('url')->nullable();
            $table->tinyInteger('status')->default(1)->comment('-1=deleted, 0=inactive, 1=active');
            $table->integer('queue')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('advertisements');
    }
};
