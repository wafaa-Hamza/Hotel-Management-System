<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ToExtendOOrdServicesOneDayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $route = $this->segment(count(request()->segments()));
        if($route == 'storeFromPreChargeToTransaction')
        {
            return [
                "pre_charge_id" => 'required|array',
                "pre_charge_id.*" => 'required|exists:pre_charges,id',
            ];
        }
        if($route == 'ToExtendOOrdServicesOneDay')
        {
            return [
                "oordServicesID" => 'required|array',
                "oordServicesID.*" => 'required|exists:oord_services,id'
            ];
        }
     
    }
}
