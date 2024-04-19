<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BudgetRequest extends FormRequest
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
        // if((in_array($this->method(), ['PUT', 'PATCH'])))
        // {
        //     return [
        //         "ledger_id"     => 'nullable|exists:ledgers,id',
        //         "year"          => 'nullable|date_format:Y',
        //         "one"      => 'nullable|numeric',
        //         "tow"      => 'nullable|numeric',
        //         "three"      => 'nullable|numeric',
        //         "four"      => 'nullable|numeric',
        //         "five"      => 'nullable|numeric',
        //         "six"      => 'nullable|numeric',
        //         "seven"      => 'nullable|numeric',
        //         "eight"      => 'nullable|numeric',
        //         "nine"      => 'nullable|numeric',
        //         "ten"      => 'nullable|numeric',
        //         "eleven"      => 'nullable|numeric',
        //         "twelve"      => 'nullable|numeric',   
        //     ];
        // }else{
            return [
                "budgets.*.ledger_id"     => 'required|exists:ledgers,id',
                 "budgets.*.year"      => 'required|date_format:Y',
                 "budgets.*.one"      => 'nullable|numeric',
                 "budgets.*.tow"      => 'nullable|numeric',
                 "budgets.*.three"      => 'nullable|numeric',
                 "budgets.*.four"      => 'nullable|numeric',
                 "budgets.*.five"      => 'nullable|numeric',
                 "budgets.*.six"      => 'nullable|numeric',
                 "budgets.*.seven"      => 'nullable|numeric',
                 "budgets.*.eight"      => 'nullable|numeric',
                 "budgets.*.nine"      => 'nullable|numeric',
                 "budgets.*.ten"      => 'nullable|numeric',
                 "budgets.*.eleven"      => 'nullable|numeric',
                 "budgets.*.twelve"      => 'nullable|numeric',              
            ];
       // }

    }
}