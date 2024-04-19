<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChargeRoutedRequest extends FormRequest
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
                'guestID_from' => 'required|exists:guests,id',
                'ledgerID' => 'required|array|exists:ledgers,id',
               'guestID_to' => 'required|exists:guests,id',
               'window_id_from' => 'required|exists:windows,id',
               'window_id_to' => 'required|exists:windows,id',
            ];
       
       
    }
}
