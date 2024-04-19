<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChartOfAccountsRequest extends FormRequest
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
                "account_name" => 'nullable|min:2|max:49|string',
                "account_name_loc" => 'nullable|min:2|max:49|string',
                "account_level" =>'nullable|exists:chart_of_account_levels,id',
                "account_class" => 'nullable|min:2|max:249|string',
                "account_type" => 'nullable',
                "is_transaction"=> 'nullable|boolean',
                "child" => 'nullable|exists:chart_of_accounts,id',
            ];
        }else{
            return [
                "account_name" => 'required|min:2|max:49|string',
                "account_name_loc" => 'required|min:2|max:49|string',
                "account_level" =>'nullable|exists:chart_of_account_levels,id',
                "account_class" => 'nullable|min:2|max:249|string',
                "account_type" => 'nullable',
                "is_transaction"=> 'nullable|boolean',
                "main_account_id" => 'required|exists:chart_of_accounts,id',
            ]; 
        }
   
    }
}
