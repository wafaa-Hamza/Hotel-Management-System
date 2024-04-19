<?php

namespace App\Http\Requests;

use App\helpers;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class AvailabilityRequest extends FormRequest
{
    use helpers;
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
        $hotelData = $this->settings()->first()->hotel_date;
        $dateNow = Carbon::now();
        return [
            'in_date' => 'required|date|after_or_equal:'.$hotelData,
            'out_date' => 'required|date|after:startDate',
            'room_type_id' => 'nullable|exists:room_types,id',
            'room_id' => 'nullable|exists:rooms,id',
            'duOut' => 'nullable|in:0,1',
        ];
    }
}
