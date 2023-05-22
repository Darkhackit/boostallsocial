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
        Schema::create('social_media_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('order_key');
            $table->foreignId('country_id')->constrained();
            $table->integer('quantity')->nullable();
            $table->string('link')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->integer('start_count')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media_orders');
    }
};
