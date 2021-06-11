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
            'family_id' => $this->owner->family_id,
            'nop' => $this->nop,
            'taxpayer_name' => $this->owner->name,
            'tax_object_name' => $this->name,
            'guardian_id' => $this->guardian_id,
        ];
    }
}
