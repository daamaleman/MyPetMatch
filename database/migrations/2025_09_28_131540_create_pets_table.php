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
            // Nota: evitamos FK directa aquí por el orden de migraciones; se puede agregar en una migración posterior
            $table->unsignedBigInteger('organization_id');
            $table->index('organization_id');
            $table->string('name');
            $table->string('species')->nullable();
            $table->string('breed')->nullable();
            $table->string('age')->nullable();
            // Campos normalizados agregados desde el inicio
            $table->unsignedSmallInteger('age_years')->nullable();
            $table->decimal('weight_kg', 5, 1)->nullable();
            $table->unsignedSmallInteger('height_cm')->nullable();
            $table->string('size')->nullable();
            $table->enum('sex', ['male','female','unknown'])->nullable();
            $table->text('story')->nullable();
            $table->string('cover_image')->nullable();
            $table->json('image_gallery')->nullable();
            $table->json('video_gallery')->nullable();
            $table->enum('status', ['draft','published','archived'])->default('draft');
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
