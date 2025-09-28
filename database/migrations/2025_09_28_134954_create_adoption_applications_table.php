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
            // Relations
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // adopter
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pet_id')->constrained()->cascadeOnDelete();

            // Workflow
            $table->enum('status', [
                'submitted',
                'under_review',
                'approved',
                'rejected',
                'withdrawn',
                'cancelled',
            ])->default('submitted');

            // Application content
            $table->text('message')->nullable(); // initial message/motivation
            $table->json('answers')->nullable(); // flexible questionnaire answers

            // Household & suitability (optional fields)
            $table->unsignedTinyInteger('adults_count')->nullable();
            $table->unsignedTinyInteger('children_count')->nullable();
            $table->boolean('has_other_pets')->default(false);
            $table->text('other_pets_details')->nullable();
            $table->enum('housing_type', ['apartment', 'house', 'other'])->nullable();
            $table->boolean('has_fenced_yard')->nullable();
            $table->boolean('has_landlord_permission')->nullable();
            $table->boolean('terms_accepted')->default(false);
            $table->enum('preferred_contact', ['email', 'phone'])->nullable();

            // Timeline
            $table->timestamp('scheduled_interview_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            // Internal (for org staff)
            $table->text('internal_notes')->nullable();

            $table->timestamps();

            // Helpful indexes
            $table->index(['organization_id', 'status']);
            $table->index(['user_id', 'pet_id']);
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
