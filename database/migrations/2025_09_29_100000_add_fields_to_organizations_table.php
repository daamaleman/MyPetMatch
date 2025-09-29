<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            if (!Schema::hasColumn('organizations', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('organizations', 'email')) {
                $table->string('email')->nullable()->after('description');
            }
            if (!Schema::hasColumn('organizations', 'phone')) {
                $table->string('phone', 50)->nullable()->after('email');
            }
            if (!Schema::hasColumn('organizations', 'city')) {
                $table->string('city', 120)->nullable()->after('phone');
            }
            if (!Schema::hasColumn('organizations', 'state')) {
                $table->string('state', 120)->nullable()->after('city');
            }
            if (!Schema::hasColumn('organizations', 'country')) {
                $table->string('country', 120)->nullable()->after('state');
            }
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            foreach (['description','email','phone','city','state','country'] as $col) {
                if (Schema::hasColumn('organizations', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
