<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CheckOutRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        // foreach($this->input('window_id.*') as $key => $value)
        // {
            return [
               'guest_id' => 'required|exists:guests,id',
                'window_id.*'  => 'required|exists:windows,id',
            ];
     
    }
}
