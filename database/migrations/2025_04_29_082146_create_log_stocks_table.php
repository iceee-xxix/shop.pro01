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
        Schema::create('log_stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('stock_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->integer('menu_option_id')->nullable();
            $table->float('old_amount')->nullable();
            $table->float('amount')->nullable();
            $table->integer('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_stocks');
    }
};
