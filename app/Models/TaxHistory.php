<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'land_id',
        'year',
        'amount',
        'payment_status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function land()
    {
        return $this->belongsTo(Land::class);
    }
}
