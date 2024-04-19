<?php

namespace App\Http\Requests;

use App\Rules\RoomNameCountRule;
use Illuminate\Foundation\Http\FormRequest;

class CopyRoomRequest extends FormRequest
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
            'FromFloor_id' => 'required|exists:floors,id',
            'toFloorID' => 'required|exists:floors,id',
           // 'numberOfDegit' => 'required|'.new RoomNameCountRule,
           'newDegit' => 'required',
            'numberOfDegit' => ['required',new RoomNameCountRule($this->FromFloor_id)],
        ];
    }
}