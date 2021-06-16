<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'modified_by',
        'source_nop',
        'new_nop',
        'tax_object_road',
        'guardian_id',
        'block_number',
        'sppt_persil_number',
        'new_taxpayer_name',
        'new_taxpayer_village',
        'new_taxpayer_road',
        'new_land_area',
        'new_land_area_unit',
        'new_building_area',
        'new_building_area_unit',
        'taxpayer_source_name',
        'taxpayer_source_village',
        'taxpayer_source_road',
        'land_source_area',
        'land_source_area_unit',
        'building_source_area',
        'building_source_area_unit',
    ];
}
