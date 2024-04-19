<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuestProfileRequest extends FormRequest
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
        $route = $this->segment(count(request()->segments()));
       // dd($route);
        
        if($route != 'storeGroupProfile')
        {
            if( (in_array($this->method(), ['PUT', 'PATCH']))){
                $guest_profile_id = substr($this->segment(count(request()->segments())),+ 3);
             return [
            // 'Profile_no' => 'nullable|min:1|max:100|unique:guest_profiles,Profile_no,'.$guest_profile_id,
             'first_name' => 'nullable|min:3|max:50',
             'last_name' => 'nullable|min:3|max:50',
             'id_no' => 'nullable|min:1|max:100',
             'mobile' => 'nullable|min:3|max:50',
             'phone' => 'nullable|min:3|max:50',
             'email' => 'nullable|max:50|email',
             'address' => 'nullable|max:254',
             'city' => 'nullable|min:3|max:254',
             'language' => 'nullable|min:2|max:2',
             'date_of_birth' => 'nullable|date',
             'gender' =>'nullable|in:male,female,no gender found',
          //   'created_by' => 'nullable|min:3|max:254',
             'country_id' => 'nullable|exists:countries,id',
             'id_type_id' => 'nullable|exists:id_types,id',
             'version_no' => 'nullable|numeric',
         ];
        }else{
            return [
            //     'Profile_no' => 'required|min:1|max:100|unique:guest_profiles,Profile_no',
                'first_name' => 'required|min:3|max:50',
                'last_name' => 'required|min:3|max:50',
                'id_no' => 'required|min:1|max:100|unique:guest_profiles,id_no',
                'mobile' => 'required|min:3|max:50',
                'phone' => 'nullable|min:3|max:50',
                'email' => 'nullable|email|min:3|max:50|unique:guest_profiles,email',
                'address' => 'nullable|min:3|max:254',
                'city' => 'nullable|min:3|max:254',
                'language' => 'nullable|min:2|max:2',
                'date_of_birth' => 'nullable|min:2|max:10',
                'gender' =>'required|in:male,female,no gender found',
            //      'created_by' => 'required|min:3|max:254',
                'country_id' => 'required|exists:countries,id',

                'id_type_id' => 'required|exists:id_types,id',
                'version_no' => 'nullable|numeric',
            ];
    
        }
     }else{
        //validation for group guest profile
        return [
        //     'Profile_no' => 'required|min:1|max:100|unique:guest_profiles,Profile_no',
            'first_name' => 'required|min:3|max:50',
            'last_name' => 'required|min:3|max:50',
            'id_no' => 'required|min:1|max:100',
            'mobile' => 'required|min:3|max:50',
            'phone' => 'nullable|min:3|max:50',
            'email' => 'nullable|email|min:3|max:50|unique:guest_profiles,email',
            'address' => 'nullable|min:3|max:254',
            'city' => 'nullable|min:3|max:254',
            'language' => 'nullable|min:2|max:2',
            'date_of_birth' => 'nullable|min:2|max:10',
            'gender' =>'required|in:male,female,no gender found',
        //      'created_by' => 'required|min:3|max:254',
            'country_id' => 'required|exists:countries,id',
            'id_type_id' => 'required|exists:id_types,id',
            'version_no' => 'nullable|numeric',
        ];

     }
 
     }
}
