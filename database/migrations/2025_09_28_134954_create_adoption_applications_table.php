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
        Schema::create('adoption_applications', function (Blueprint $table) {
            $table->id();
            // Relaciones
            $table->unsignedBigInteger('user_id'); // solicitante (adoptante)
            $table->unsignedBigInteger('organization_id'); // dueña de la mascota
            $table->unsignedBigInteger('pet_id');
            // Estado del proceso
            $table->enum('status', ['pending','under_review','approved','rejected'])->default('pending');
            // Campos adicionales opcionales
            $table->text('message')->nullable();
            $table->json('answers')->nullable();
            $table->timestamps();

            // Índices y FKs
            $table->index(['organization_id','status']);
            $table->index(['user_id','status']);
            $table->index('pet_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete();
            $table->foreign('pet_id')->references('id')->on('pets')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adoption_applications');
    }
};
