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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // Campos adicionales desde el inicio (evitamos migración separada)
            $table->text('description')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('city', 120)->nullable();
            $table->string('state', 120)->nullable();
            $table->string('country', 120)->nullable();
            $table->timestamps();
        });

        // Intentar agregar la clave foránea desde users.organization_id -> organizations.id
        // Si la columna ya existe en users, agregamos la FK de forma segura.
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'organization_id')) {
                try {
                    $table->foreign('organization_id')->references('id')->on('organizations')->nullOnDelete();
                } catch (\Throwable $e) {
                    // Ignorar si ya existe
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Quitar FK en users si existe antes de eliminar organizations
        Schema::table('users', function (Blueprint $table) {
            try { $table->dropForeign(['organization_id']); } catch (\Throwable $e) {}
        });
        Schema::dropIfExists('organizations');
    }
};
