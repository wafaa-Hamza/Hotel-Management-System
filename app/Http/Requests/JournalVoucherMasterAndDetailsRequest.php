<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JournalVoucherMasterAndDetailsRequest extends FormRequest
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
                "jv_date" => 'nullable|date',
                "jv_type_id" => 'nullable|exists:journal_voucher_types,id',
                "total_debit" => 'nullable|numeric',
                "total_credit"=> 'nullable|numeric',
                "mdescription" => "nullable",
                
                'details.*.jv_srl' => 'required|numeric',
                'details.*.account_id' => 'required|exists:chart_of_accounts,id',
                'details.*.reference' => 'nullable|min:2|max:254',
                'details.*.descriptions' => 'nullable|min:2|max:254',
                'details.*.credit' => 'nullable',
                'details.*.debit' => 'nullable',
            ];
        }else{
            return [
                "jv_date" => 'required|date',
                "jv_type_id" => 'required|exists:journal_voucher_types,id',
                "total_debit" => 'required|numeric',
                "total_credit"=> 'required|numeric',
                "mdescription" => "nullable",

                'details.*.jv_srl' => 'required|numeric',
                'details.*.account_id' => 'required|exists:chart_of_accounts,id',
                'details.*.reference' => 'nullable|min:2|max:254',
                'details.*.descriptions' => 'nullable|min:2|max:254',
                'details.*.credit' => 'required_without_all:debit',
                'details.*.debit' => 'required_without_all:credit',
            ];
        }
     
    }
}
