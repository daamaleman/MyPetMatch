<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id','name','species','breed','age','size','sex','story','cover_image','image_gallery','video_gallery','status',
        // Opcionales si existen en BD (migración futura)
        'age_years','weight_kg','height_cm'
    ];

    protected $casts = [
        'image_gallery' => 'array',
        'video_gallery' => 'array',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
