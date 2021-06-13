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

    protected $with = [
        'family',
    ];

    public function land()
    {
        return $this->hasOne(Land::class);
    }

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function($data) {
            $data->land()->delete();
        });
    }
}
