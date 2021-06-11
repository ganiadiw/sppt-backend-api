<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Land extends Model
{
    use HasFactory;

    protected $fillable = [
        'nop',
        'owner_id',
        'guardian_id',
        'rt',
        'rw',
        'village',
        'road',
        'determination',
        'sppt_persil_number',
        'land_area',
        'land_area_unit',
        'building_area',
        'building_area_unit',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $with = ['owner'];
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}
