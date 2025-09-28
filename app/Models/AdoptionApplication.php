<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdoptionApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id',
        'pet_id',
        'status',
        'message',
        'answers',
        'adults_count',
        'children_count',
        'has_other_pets',
        'other_pets_details',
        'housing_type',
        'has_fenced_yard',
        'has_landlord_permission',
        'terms_accepted',
        'preferred_contact',
        'scheduled_interview_at',
        'reviewed_at',
        'internal_notes',
    ];

    protected $casts = [
        'answers' => 'array',
        'has_other_pets' => 'boolean',
        'has_fenced_yard' => 'boolean',
        'has_landlord_permission' => 'boolean',
        'terms_accepted' => 'boolean',
        'scheduled_interview_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    // Query scopes
    public function scopeForOrg($query, int $orgId)
    {
        return $query->where('organization_id', $orgId);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForPet($query, int $petId)
    {
        return $query->where('pet_id', $petId);
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    // Status helpers
    public function isSubmitted(): bool { return $this->status === 'submitted'; }
    public function isUnderReview(): bool { return $this->status === 'under_review'; }
    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }
    public function isWithdrawn(): bool { return $this->status === 'withdrawn'; }
    public function isCancelled(): bool { return $this->status === 'cancelled'; }
}
