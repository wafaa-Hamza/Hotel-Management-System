<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CancelRequest extends FormRequest
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
            "guest_id" => "required_if:groubeCode,null",
            "groubeCode" => "required_if:guest_id,null",
            "cancelReason" => "required|in:0,1,2,3,4,5,6",
        ];
    }
}
