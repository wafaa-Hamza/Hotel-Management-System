<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MoreNameRequest extends FormRequest
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
if((in_array($this->method(), ['PUT', 'PATCH']))) {
    return [
        'data.*.guest_id' => 'nullable|exists:guests,id',
        'data.*.profile_id' => 'nullable|exists:guest_profiles,id',
        'data.*.country_id' => 'nullable|exists:countries,id',
        'data.*.first_name' => 'nullable|min:2|max:50',
        'data.*.last_name' => 'nullable|min:2|max:50',
        'data.*.mobile' => 'nullable|min:2|max:20',
        'data.*.id_no' => 'nullable|min:2|max:30',
    ];

}else{
    return [
        'data.*.guest_id' => 'nullable|exists:guests,id',
        'data.*.profile_id' => 'nullable|exists:guest_profiles,id',
        'data.*.country_id' => 'nullable|exists:countries,id',
        'data.*.first_name' => 'nullable|min:2|max:50',
        'data.*.last_name' => 'nullable|min:2|max:50',
        'data.*.mobile' => 'nullable|min:2|max:20',
        'data.*.id_no' => 'nullable|min:2|max:30',
    ];

}
   
    }
}
