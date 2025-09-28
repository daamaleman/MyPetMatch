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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('species'); // dog, cat, etc.
            $table->string('breed')->nullable();
            $table->enum('sex', ['male', 'female', 'unknown'])->default('unknown');
            $table->unsignedTinyInteger('age_years')->nullable();
            $table->unsignedTinyInteger('age_months')->nullable();
            $table->enum('size', ['xs','s','m','l','xl'])->nullable();
            $table->string('color')->nullable();
            $table->text('description')->nullable();
            $table->longText('story')->nullable();
            $table->string('cover_image')->nullable();
            $table->json('image_gallery')->nullable(); // array of image URLs/paths
            $table->json('video_gallery')->nullable(); // array of video URLs/ids
            $table->enum('status', ['available', 'pending', 'adopted', 'inactive'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
