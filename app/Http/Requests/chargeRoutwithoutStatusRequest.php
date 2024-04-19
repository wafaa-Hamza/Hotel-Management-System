<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class chargeRoutwithoutStatusRequest extends FormRequest
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
        return [
            'guestID_from' => 'required|exists:guests,id',
            'window_id_from' => 'required|exists:windows,id',
            'routingTo*.ledgerID' => 'required|array|exists:ledgers,id',
           'routingTo*.guestID_to' => 'required|exists:guests,id',
           'routingTo*.window_id_to' => 'required|exists:windows,id',
        ];
    }
}
