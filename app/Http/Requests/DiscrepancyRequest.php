<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiscrepancyRequest extends FormRequest
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
        if((in_array($this->method(), ['PUT', 'PATCH'])))
        {
            return [
                'room_id' => 'nullable|exists:rooms,id',
                'status_fo' => 'nullable|in:VA,OC,OO,OS,BL,DO',
                'status_hk' => 'nullable|in:DI,CL'
            ];
        }else{
            return [
                'room_id' => 'required|exists:rooms,id',
                'status_fo' => 'nullable|in:VA,OC,OO,OS,BL,DO',
                'status_hk' => 'nullable|in:DI,CL'
            ];
        }

    }
}
