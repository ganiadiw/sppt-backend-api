<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewMutationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'new_data' => [
                'id' => $this->id,                
                'tax_object' => [
                    'nop' => $this->nop,
                    'guardian_id' => $this->guardian_id,
                    'address' => [
                        'rt' => $this->rt,
                        'rw' => $this->rw,
                        'village' => $this->village,
                        'road' => $this->road,
                    ],
                    'determination' => $this->determination,
                    'sppt_persil_number' => $this->sppt_persil_number,
                    'block_number' => $this->block_number,
                    'land_area' => $this->land_area,
                    'land_area_unit' => $this->land_area_unit,
                    'building_area' => $this->building_area,
                    'building_area_unit' => $this->building_area_unit
                ],
                'taxpayer' => [
                    'name' => $this->owner->name,
                    'address' => [
                        'rt' => $this->owner->rt,
                        'rw' => $this->owner->rw,
                        'village' => $this->owner->village,
                        'road' => $this->owner->road,
                    ],
                ]
            ]
        ];
    }
}
