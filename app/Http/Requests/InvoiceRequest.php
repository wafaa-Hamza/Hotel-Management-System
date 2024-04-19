<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
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
        return [
            "window_id" => 'nullable:exists:windows,id',
            "total_room_charge" => 'nullable|numeric',
            "total_fb_charge" => 'nullable|numeric',
            "total_other_charge" => 'nullable|numeric',
            "total_payment" => 'nullable|numeric',
        ];
    }
}
