<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupReservationByIdsRequest extends FormRequest
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
            "guest_ids"=> "nullable|array",
            "guest_ids.*"=> "exists:guests,id",
            "in_date" => "nullable|date",
            "out_date" => "nullable|date",
            "vip"=>"nullable|in:A,B,C",
            "rate_code" => "exists:rate_codes,id",
            "rm_rate" => "nullable",
            "pax" => "nullable"
        ];
    }
}