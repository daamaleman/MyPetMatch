<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'name',
        'species',
        'breed',
        'sex',
        'age_years',
        'age_months',
        'size',
        'color',
        'description',
        'story',
        'cover_image',
        'image_gallery',
        'video_gallery',
        'status',
    ];

    protected $casts = [
        'image_gallery' => 'array',
        'video_gallery' => 'array',
    ];

    /**
     * Owning organization
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Adoption applications for this pet
     */
    public function adoptionApplications(): HasMany
    {
        return $this->hasMany(AdoptionApplication::class);
    }
}
