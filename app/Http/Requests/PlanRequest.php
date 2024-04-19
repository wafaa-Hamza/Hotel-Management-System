<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
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
       
        /**
         * this if for createPlanFeature request
         * elseif for update request
         * else for sotore request
         */
        if($this->featureID){
            return [
                'featureID' => 'required|exists:features,id',
                'charges' => 'nullable',
            ];

        }
        if( (in_array($this->method(), ['PUT', 'PATCH']))){
    
            return [
                'grace_days' => 'nullable',
                'name' => 'nullable|max:254|unique:plans,name,'.$this->segment(count(request()->segments())),
                'periodicity_type' => 'nullable|max:254|in:Day,Week,Month,Year',
                'plan_price' => 'nullable|numeric',
            ];
        }else{
            return [

                'name' => 'nullable|unique:plans|max:254',
                'grace_days' => 'nullable',
                'periodicity' => 'required',
                'periodicity_type' => 'required|max:254|in:Day,Week,Month,Year',
                'featureID' => 'nullable|exists:features,id',
                'plan_price' => 'required|numeric',
            ];
        }
       
    }
}
