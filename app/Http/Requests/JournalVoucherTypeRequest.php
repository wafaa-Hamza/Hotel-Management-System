<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JournalVoucherTypeRequest extends FormRequest
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
            'name' => 'nullable|min:3|max:49',
                'name_loc' =>  'nullable|min:3|max:49',
                'is_opening' => 'nullable|boolean',
                'voucher_type' => 'nullable|in:JV,RVS,PVS'
        ];
    }else{
        return [
                'name' => 'required|min:3|max:49',
                'name_loc' =>  'required|min:3|max:49',
  
                'is_opening' => 'nullable|boolean',
                'voucher_type' => 'required|in:JV,RVS,PVS'
        ];
        
    }

    
    }
}