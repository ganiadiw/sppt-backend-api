<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaxHistoryRequest extends FormRequest
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
            'sppt_id' => ['required'],
            'year' => ['required'],
            'amount' => ['required', 'numeric'],
            'payment_status' => ['required']
        ];
    }
}
