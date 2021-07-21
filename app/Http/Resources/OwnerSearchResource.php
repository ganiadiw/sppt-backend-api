<?php

namespace App\Http\Resources;

use App\Models\TaxHistory;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OwnerSearchResource extends JsonResource
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
                'nop' => (string)$this->land->nop,
                'guardian_id' => $this->land->guardian_id,
                'address' => [
                    'rt' => $this->land->rt,
                    'rw' => $this->land->rw,
                    'village' => $this->land->village,
                    'road' => $this->land->road,
                ],
                'sppt_persil_number' => $this->land->sppt_persil_number,
                'block_number' => $this->land->block_number,
                'land_area' => $this->land->land_area,
                'land_area_unit' => $this->land->land_area_unit,
                'building_area' => $this->land->building_area,
                'building_area_unit' => $this->land->building_area_unit
            ],
            'taxpayer' => [
                'name' => $this->name,
                'address' => [
                    'rt' => $this->rt,
                    'rw' => $this->rw,
                    'village' => $this->village,
                    'road' => $this->road,
                ],
                'family' => [
                    'id' => $this->family->id,
                    'name' => $this->family->name,
                    'rt' => $this->family->rt,
                    'rw' => $this->family->rw,
                    'village' => $this->family->village,
                    'road' => $this->family->road,
                ]
            ],
            'current_tax_amount' => $this->land->taxHistories->firstWhere('year', Carbon::now()->format('Y'))->amount ?? 0,
            'current_tax_year' => $this->land->taxHistories->firstWhere('year', Carbon::now()->format('Y'))->year ?? null,
            'current_tax_payment_status' => $this->land->taxHistories->firstWhere('year', Carbon::now()->format('Y'))->payment_status ?? null
        ];
    }
}
