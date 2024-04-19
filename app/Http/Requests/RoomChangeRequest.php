<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomChangeRequest extends FormRequest
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
            'guest_id'=> 'required|exists:guests,id',
        'from_room_number' => 'required|exists:rooms,id',
        'to_room_number'=> 'required|exists:rooms,id',
        'reason'=> 'required|min:2',
        ];
    }
}
