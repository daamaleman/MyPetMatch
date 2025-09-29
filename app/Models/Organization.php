<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = ['name','description','email','phone','city','state','country'];

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }
}
