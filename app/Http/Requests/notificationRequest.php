<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class notificationRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if(in_array(request()->method(),['PUT','PATCH']))
        {
            return [
                'start_date' => 'nullable|date',
                'message' => 'nullable|string|min:2|max:254',
                'message_loc' => 'nullable|string',
                'redirect_to' => 'nullable|string',
                'is_main' => 'nullable|boolean',
                'active' => 'nullable|boolean',
                'added_by' => 'nullable|string',
            ];
        }else{
            return [
                'start_date' => 'required|date',
                'message' => 'required|string|min:2|max:254',
                'message_loc' => 'required|string',
                'redirect_to' => 'nullable|string',
                'is_main' => 'nullable|boolean',
                'active' => 'nullable|boolean',
                'added_by' => 'nullable|string',
            ];
        }
       
    }
}
