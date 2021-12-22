<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
            'name' => ['required'],
            'occupation' => ['required'],
            'username' => ['required', 'max:100', Rule::unique('users')->ignore(Auth::id())],
            'email' => ['required', Rule::unique('users')->ignore(Auth::id())],
            'image' => ['image', 'mimes:png,jpg,jpeg'],
            '_method' => ['required'],
            'confirmation_password' => ['min:8'],
            'new_password' => ['min:8']
        ];
    }
}
