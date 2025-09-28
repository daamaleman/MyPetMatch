<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'organization_id')) {
                $table->unsignedBigInteger('organization_id')->nullable()->after('role');
                $table->index('organization_id');
                // Add FK if organizations table exists
                if (Schema::hasTable('organizations')) {
                    try {
                        $table->foreign('organization_id')->references('id')->on('organizations')->nullOnDelete();
                    } catch (\Throwable $e) {
                        // ignore if FK already exists
                    }
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop FK if exists
            try { $table->dropForeign(['organization_id']); } catch (\Throwable $e) {}
        });
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'organization_id')) {
                $table->dropColumn('organization_id');
            }
        });
    }
};
