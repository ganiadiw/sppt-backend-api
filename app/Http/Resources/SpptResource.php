<?php

namespace App\Http\Resources;

use App\Models\TaxHistory;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SpptResource extends JsonResource
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
            'id' => $this->id,
            'tax_object' => [
                'nop' => (string)$this->nop,
                'guardian_id' => $this->guardian_id,
                'address' => [
                    'rt' => $this->rt,
                    'rw' => $this->rw,
                    'village' => $this->village,
                    'road' => $this->road,
                ],
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
                'family' => [
                    'id' => $this->owner->family->id,
                    'name' => $this->owner->family->name,
                    'rt' => $this->owner->family->rt,
                    'rw' => $this->owner->family->rw,
                    'village' => $this->owner->family->village,
                    'road' => $this->owner->family->road,
                ]
            ],
            'current_tax_amount' => $this->taxHistories->firstWhere('year', Carbon::now()->format('Y'))->amount ?? null,
            'current_tax_year' => $this->taxHistories->firstWhere('year', Carbon::now()->format('Y'))->year ?? null,
            'current_tax_payment_status' => $this->taxHistories->firstWhere('year', Carbon::now()->format('Y'))->payment_status ?? null
        ];
    }
}
