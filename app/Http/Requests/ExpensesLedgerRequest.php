<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpensesLedgerRequest extends FormRequest
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
        if((in_array($this->method(),['PUT','PATCH'])))
        {
            return [
                "name" => "nullable|min:2|max:49",
                "name_loc"=> "nullable|required|min:2|max:49",
                "gl_acount"=> "nullable"
            ];
        }else{
            return [
                "name" => "required|min:2|max:49",
                "name_loc"=> "required|min:2|max:49",
                "gl_acount"=> "required"
            ];
        }
      
    }
}
