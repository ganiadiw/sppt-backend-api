<?php

namespace App\Http\Resources;

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
            'family_id' => $this->family_id,
            'family_name' => $this->family->name,
            'nop' => $this->land->nop,
            'taxpayer_name' => $this->name,
            'guardian_id' => $this->land->guardian_id,
        ];
    }
}
