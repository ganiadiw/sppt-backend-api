<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_id',
        'name',
        'rt',
        'rw',
        'village',
        'road',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function lands()
    {
        return $this->hasMany(Land::class);
    }

    public function family()
    {
        return $this->belongsTo(Family::class);
    }
}
