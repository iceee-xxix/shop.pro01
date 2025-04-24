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
        Schema::create('configs', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable();
            $table->text('color1')->nullable();
            $table->text('color2')->nullable();
            $table->text('color_font')->nullable();
            $table->text('color_category')->nullable();
            $table->text('image_bg')->nullable();
            $table->text('image_qr')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configs');
    }
};
