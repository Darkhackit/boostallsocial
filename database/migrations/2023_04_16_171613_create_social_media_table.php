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
        Schema::create('social_media', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('format')->nullable();
            $table->boolean('show_link')->default(false);
            $table->boolean('popular_service')->default(false);
            $table->boolean('show_comments')->default(false);
            $table->boolean('show_quantity')->default(false);
            $table->boolean('show_usernames')->default(false);
            $table->boolean('show_username')->default(false);
            $table->boolean('show_hashtags')->default(false);
            $table->boolean('show_answer_number')->default(false);
            $table->boolean('show_groups')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media');
    }
};
