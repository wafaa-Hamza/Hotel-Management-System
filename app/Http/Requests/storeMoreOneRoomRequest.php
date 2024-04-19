<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeMoreOneRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create-room', Room::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
                'rooms.*.room_name_en' => 'required|unique:rooms,rm_name_en|max:254',
                'rooms.*.room_name_loc' => 'nullable|unique:rooms,rm_name_loc|max:254',
                'rooms.*.room_max_pax' => 'nullable|numeric',
                'rooms.*.room_phone_no' => 'nullable',
                'rooms.*.room_phone_ext' => 'nullable',
                'rooms.*.room_key_code' => 'nullable|min:3|max:254',
                'rooms.*.room_key_options' => 'nullable|min:3|max:254',
                'rooms.*.room_connection' => 'nullable|boolean',
                'rooms.*.fo_status' => 'nullable|in:VA,OC,OO,OS',
                'rooms.*.room_active' => 'nullable|boolean',
                'rooms.*.hk_stauts' => 'nullable|in:DI,CL',
                'rooms.*.sort_order' => 'nullable|numeric', 
                'rooms.*.room_type_id' => 'required|exists:room_types,id',
                'rooms.*.floor_id' => 'required|exists:floors,id',
                'rooms.*.room_statuse_id' => 'nullable|exists:room_statuses,id',
        ];
    }
}
