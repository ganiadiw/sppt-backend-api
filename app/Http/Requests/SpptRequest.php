<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpptRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'taxpayer.family_id' => ['required'],
            'taxpayer.name' => ['required', 'max:100'],
            'taxpayer.rt'  => ['required', 'max:10'],
            'taxpayer.rw' => ['required', 'max:10'],
            'taxpayer.village' => ['required', 'max:100'],
            'taxpayer.road' => ['max:100'],
            // 'guardian_id' => ['required'],
            // 'tax_object_rt' => ['required', 'max:10'],
            // 'tax_object_rw' => ['required', 'max:10'],
            // 'tax_object_village' => ['required', 'max:100'],
            // 'tax_objcet_road' => ['max:100'],
            // 'sppt_persil_number' => ['max:20'],
            // 'block_number' => ['required', 'max:20'],
            // 'land_area' => ['required'],
            // 'land_area_unit' => ['required', 'max:10'],
            // 'building_area' => ['required'],
            // 'building_area_unit' => ['required', 'max:10'],
        ];
    }
}
