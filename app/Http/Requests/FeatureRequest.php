<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeatureRequest extends FormRequest
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
        if( (in_array($this->method(), ['PUT', 'PATCH']))){
            return [
                'quota'       => 'nullable|max:254|boolean',
                'postpaid'       => 'nullable|max:254|boolean',
                'periodicity'       => 'nullable',
                'consumable'       => 'nullable|boolean',
                'name' => 'nullable|max:254|unique:features,name,'.$this->segment(count(request()->segments())),
                'periodicity_type' => 'nullable|max:254|in:Day,Week,Month,Year',
            ];
        }else{
            return [            
                'periodicity' => 'required',
                'name' => 'required|unique:features|max:254',
                'periodicity_type' => 'nullable|max:254|in:Day,Week,Month,Year',
            ];
        }
    }
}
