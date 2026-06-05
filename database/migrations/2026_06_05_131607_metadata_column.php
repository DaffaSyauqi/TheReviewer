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
        Schema::table('place_images', function (Blueprint $table) {
            $table->dropColumn(['metadata', 'is_thumbnail']);
            $table->string('disk');
            $table->string('path');
            $table->string('mime_type');
            $table->integer('size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('place_images', function (Blueprint $table) {
            $table->dropColumn(['disk', 'path', 'mime_type', 'size']);
            $table->string('image_url');
            $table->boolean('is_thumbnail')->default(false);
        });
    }
};
