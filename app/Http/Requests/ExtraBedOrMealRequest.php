<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExtraBedOrMealRequest extends FormRequest
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
        if(in_array($this->method(),['PUT','PATCH']))
        {
            return [
                'name' => 'nullable|max:254',
                'name_loc' => 'nullable|max:254',
                'ledger_id' => 'nullable|exists:ledgers,id',
            ];
        }else{
            return [
                'name' => 'required|max:254',
                'name_loc' => 'required|max:254',
                'ledger_id' => 'required|exists:ledgers,id',
            ];
        }
    }
}