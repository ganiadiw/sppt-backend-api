<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'modified_by',
        'new_taxpayer_name',
        'new_taxpayer_village',
        'new_taxpayer_road',
        'new_nop',
        'guardian_id',
        'new_tax_object_name',
        'new_tax_object_road',
        'sppt_persil_number',
        'block_number',
        'new_land_area',
        'new_land_area_unit',
        'new_building_area',
        'new_building_area_unit',
        'taxpayer_source_name',
        'taxpayer_source_village',
        'taxpayer_source_road',
        'nop_source',
        'tax_source_object_name',
        'tax_source_object_road',
        'land_source_area',
        'land_source_area_unit',
        'building_source_area',
        'building_source_area_unit',
    ];
}
