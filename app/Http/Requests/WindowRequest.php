<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WindowRequest extends FormRequest
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
        return [
            'guest_id' => 'required|exists:guests,id',
            'window_number' => 'required',
            'window_name'   => 'required|min:3|max:49',
            'invoice_number'=> 'required',
        ];
    }
}
