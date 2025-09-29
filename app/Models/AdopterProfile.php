<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdopterProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','phone','address_line1','address_line2','city','state','country','zip'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
