<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpensesRequest extends FormRequest
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
        if(in_array($this->method(),['PUT','PATCH']))
        {
            return [
                'description' => 'nullable|string',
                'name' => 'nullable|string|min:2|max:49',
                'reference' => 'nullable|string|min:2|max:249',
                'amount' => 'nullable|numeric',
                'exp_ledger_id' => 'nullable|exists:expenses_ledgers,id',
                'file' => 'nullable|max:2048'
            ];

        }else{
            return [
                'description' => 'nullable|string',
                'name' => 'nullable|string|min:2|max:49',
                'reference' => 'nullable|string|min:2|max:249',
                'amount' => 'required|numeric',
                'exp_ledger_id' => 'required|exists:expenses_ledgers,id',
                'file' => 'nullable|max:2048'
            ];
        }
  
    }
}
