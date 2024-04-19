<?php

namespace App\Http\Requests;

use App\Models\Room;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\Access\AuthorizationException;

class RoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
if((in_array($this->method(), ['PUT', 'PATCH']))) {
    return  $this->user()->can('edit-room', Room::class);
    
}else{
    return $this->user()->can('create-room', Room::class);
}
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules() 
    {
        if( (in_array($this->method(), ['PUT', 'PATCH']))){
          //  dd($this->segment(3));
    
            return [
               
                //'room_name_en' => 'nullable|max:254|unique:rooms,rm_name_en,'.$this->segment(count(request()->segments())),
                // 'room_name_en' => ['nullable',
                // Rule::unique('rooms')->ignore($this->rm_name_en)],
                //'room_name_loc' => 'nullable|max:254|unique:rooms,rm_name_loc,'.$this->segment(count(request()->segments())),
                'room_max_pax' => 'nullable|numeric|max:99',
                'room_phone_no' => 'nullable',
                'room_phone_ext' => 'nullable',
                'room_key_code' => 'nullable|max:254',
                'room_key_options' => 'nullable|max:254',
                'room_connection' => 'nullable|boolean',
                'fo_status' => 'nullable|in:VA,OC,OO,OS',
                'room_active' => 'nullable|boolean',
//                'hk_stauts' => 'nullable|in:DI,CN',
                'sort_order' => 'nullable|numeric',
                'room_type_id' => 'nullable|exists:room_types,id',
                'floor_id' => 'nullable|exists:floors,id',
                'room_statuse_id' => 'nullable|exists:room_statuses,id',
              
            ];
        }else{
            return [
                'room_name_en' => 'required|unique:rooms,rm_name_en|max:254',
                'room_name_loc' => 'required|unique:rooms,rm_name_loc|max:254',
                'room_max_pax' => 'nullable|numeric',
                'room_phone_no' => 'nullable',
                'room_phone_ext' => 'nullable',
                'room_key_code' => 'nullable|max:254',
                'room_key_options' => 'nullable|max:254',
                'room_connection' => 'nullable|boolean',
                'fo_status' => 'nullable|in:VA,OC,OO,OS',
                'room_active' => 'nullable|boolean',
                'hk_stauts' => 'nullable|in:DI,CL',
                'sort_order' => 'nullable|numeric', 
                'room_type_id' => 'required|exists:room_types,id',
                'floor_id' => 'required|exists:floors,id',
                'room_statuse_id' => 'nullable|exists:room_statuses,id',

            ];
        }
    }
}
