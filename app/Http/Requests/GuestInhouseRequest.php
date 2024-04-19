<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuestInhouseRequest extends FormRequest
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
        $updateRoute = $this->segment(count(request()->segments())-1);
    if((in_array($this->method(), ['PUT', 'PATCH'])) && $updateRoute != 'reservation') {

        return [
            'folio_no' => 'nullable|min:3|max:99',
            'in_date'=> 'nullable|date',
            'out_date'=> 'nullable|date',
            'original_out_date'=> 'nullable|date',
            'no_of_nights'=> 'nullable|numeric|max:9999999',
            'rm_rate'=> 'nullable|numeric',
            'taxes'=> 'nullable|numeric',
            'no_of_pax'=> 'nullable|numeric|max:999999',
            'hotel_note'=> 'nullable',
            'client_note'=> 'nullable',
            'way_of_payment'=> 'nullable|min:3|max:49',
            'profile_id'=> 'nullable|exists:guest_profiles,id',
            'company_id'=> 'nullable|exists:company_profiles,id',
            'room_id'=> 'nullable|exists:rooms,id',
            'room_type_id'=> 'nullable|exists:room_types,id',
            'rate_code_id'=> 'nullable|exists:rate_codes,id',
            'market_segment_id'=> 'nullable|exists:markets,id',
            'source_of_business_id'=> 'nullable|exists:businesses,id',
            'meal_id'=> 'nullable|exists:meals,id',
            'meal_package_id'=> 'nullable|exists:meal_packages,id',
           // 'created_by'=> 'required|exists:users,id',
            'created_inhousGuest_at'=> 'nullable|date',
            'checked_out'=> 'nullable|in:0,1',
            'checkout_by'=> 'nullable|exists:users,id',
            'checkout_at'=> 'nullable|date',
            'is_reservation'=> 'nullable|in:0,1',
            'res_status'=> 'nullable|min:3|max:254',
            'is_group'=> 'nullable|in:0,1',
            'group_code'=> 'nullable|min:3|max:254',
            'is_dummy'=> 'nullable|in:0,1',
            'Is_PM'=> 'nullable|in:0,1',
            'is_cancel'=> 'nullable|in:0,1',
            'is_checked_in'=> 'nullable|in:0,1',
            'res_no'=> 'nullable',
            'ref_no' => 'nullable|max:49',
            'is_online' => 'nullable|in:0,1',
            'inv_address' => 'nullable|min:2|max:254',
            'company_name' => 'nullable|min:2|max:254',
            'vat_no' => 'nullable|min:2|max:254',
            'customerType' => 'nullable|in:1,2,3,4',
            'scth_transaction_id' => 'nullable',

            'is_sent_shomoos' => 'nullable|boolean',
            'shomoos_id' => 'nullable',
            'no_of_child' => 'nullable|numeric',
            'no_of_inf' => 'nullable|numeric',

            'purposeOfVisit' => 'nullable|in:1,2,3,4,5,6,7,8,9,10',

             //validation of table res_rate_days
             'res_rate_days.*.day_date'=> 'nullable|date',
             'res_rate_days.*.rm_rate'=> 'nullable|numeric',
             'res_rate_days.*.rate_id'=> 'nullable|exists:rate_codes,id',
             'res_rate_days.*.meal_id'=> 'nullable|exists:meals,id',
             'res_rate_days.*.meal_package_id'=> 'nullable|exists:meal_packages,id',

             //moreName request of table more_name
             'moreName.*.profile_id' => 'nullable|exists:guest_profiles,id',
             'moreName.*.country_id' => 'nullable|exists:countries,id',
             'moreName.*.first_name' => 'nullable|min:2|max:50',
             'moreName.*.last_name' => 'nullable|min:2|max:50',
             'moreName.*.mobile' => 'nullable|min:2|max:20',
             'moreName.*.id_no' => 'nullable|min:2|max:30',
        ];
    }elseif($route == 'reservation' && !$this->onlyNameData){
        if((in_array($this->method(), ['PUT', 'PATCH'])) && $updateRoute != 'reservation')
        {
            return [
                'file' => 'nullable|mimes:jpeg,png,pdf',
                'folio_no' => 'nullable|min:3|max:99',
                'in_date'=> 'nullable|date',
                'out_date'=> 'nullable|date',
                'original_out_date'=> 'nullable|date',
                'no_of_nights'=> 'nullable|numeric|max:9999999',
                'rm_rate'=> 'nullable|numeric',
                'taxes'=> 'nullable|numeric',
                'no_of_pax'=> 'nullable|numeric|max:999999',
                'hotel_note'=> 'nullable',
                'client_note'=> 'nullable',
                'way_of_payment'=> 'nullable|min:3|max:49',
                'profile_id'=> 'nullable|exists:guest_profiles,id',
                'company_id'=> 'nullable|exists:company_profiles,id',
                'room_id'=> 'nullable|exists:rooms,id',
                'room_type_id'=> 'nullable|exists:room_types,id',
                'rate_code_id'=> 'nullable|exists:rate_codes,id',
                'market_segment_id'=> 'nullable|exists:markets,id',
                'source_of_business_id'=> 'nullable|exists:businesses,id',
                'meal_id'=> 'nullable|exists:meals,id',
                'meal_package_id'=> 'nullable|exists:meal_packages,id',
    
               // 'created_by'=> 'required|exists:users,id',
                'created_inhousGuest_at'=> 'nullable|date',
                'checked_out'=> 'nullable|boolean',
                'checkout_by'=> 'nullable|exists:users,id',
                'checkout_at'=> 'nullable|date',
                'is_reservation'=> 'nullable|boolean',
                'res_status'=> 'nullable|in:CNF,NCF,CNC,NSH',
                'is_group'=> 'nullable|boolean',
                'group_code'=> 'nullable|min:3|max:254',
                'is_dummy'=> 'nullable|boolean',
                'Is_PM'=> 'nullable|boolean',
                'res_no' => 'nullable|max:49',
                'ref_no' => 'nullable|max:49',
                'inv_address' => 'nullable|min:2|max:254',
                'company_name' => 'nullable|min:2|max:254',
                'vat_no' => 'nullable|min:2|max:254',
                'vip' => 'nullable|in:A,B,C',
                'res_type' => 'nullable|in:D,M,Y',
                'customerType' => 'nullable|in:1,2,3,4',
                'scth_transaction_id' => 'nullable',
                'purposeOfVisit' => 'nullable|in:1,2,3,4,5,6,7,8,9,10',

                'is_sent_shomoos' => 'nullable|boolean',
                'shomoos_id' => 'nullable',
                'no_of_child' => 'nullable|numeric',
                'no_of_inf' => 'nullable|numeric',
                // //'res_rate_days.*.day_date' => Rule::requiredIf($this->input('res_type') === 'D' || $this->input('res_type') === null || !$this->has('res_type')),
                // 'res_rate_days.*.day_date' =>'required_if:res_type,D,null|date',
    
                //  //validation of table res_rate_days
                // // 'res_rate_days.*.day_date'=> 'nullable|date',
                //  'res_rate_days.*.rm_rate'=> 'nullable|numeric',
                //  'res_rate_days.*.rate_id'=> 'nullable|exists:rate_codes,id',
                //  'res_rate_days.*.meal_id'=> 'nullable|exists:meals,id',
                //  'res_rate_days.*.meal_package_id'=> 'nullable|exists:meal_packages,id',
                 
            ];
        }else{
            return [
                'file' => 'nullable|mimes:jpeg,png,pdf',
                'folio_no' => 'nullable|min:3|max:99',
                'in_date'=> 'required|date',
                'out_date'=> 'required|date',
                'original_out_date'=> 'nullable|date',
                'no_of_nights'=> 'required|numeric|max:9999999',
                'rm_rate'=> 'required|numeric',
                'taxes'=> 'nullable|numeric',
                'no_of_pax'=> 'required|numeric|max:999999',
                'hotel_note'=> 'nullable',
                'client_note'=> 'nullable',
                'way_of_payment'=> 'required|min:3|max:49',
                'profile_id'=> 'required|exists:guest_profiles,id',
                'company_id'=> 'required|exists:company_profiles,id',
                'room_id'=> 'nullable|exists:rooms,id',
                'room_type_id'=> 'required|exists:room_types,id',
                'rate_code_id'=> 'nullable|exists:rate_codes,id',
                'market_segment_id'=> 'required|exists:markets,id',
                'source_of_business_id'=> 'required|exists:businesses,id',
                'meal_id'=> 'nullable|exists:meals,id',
                'meal_package_id'=> 'nullable|exists:meal_packages,id',
    
               // 'created_by'=> 'required|exists:users,id',
                'created_inhousGuest_at'=> 'nullable|date',
                'checked_out'=> 'nullable|boolean',
                'checkout_by'=> 'nullable|exists:users,id',
                'checkout_at'=> 'nullable|date',
                'is_reservation'=> 'nullable|boolean',
                'res_status'=> 'required|in:CNF,NCF,CNC,NSH',
                'is_group'=> 'nullable|boolean',
                'group_code'=> 'nullable|min:3|max:254',
                'is_dummy'=> 'nullable|boolean',
                'Is_PM'=> 'nullable|boolean',
                'res_no' => 'nullable|max:49',
                'ref_no' => 'nullable|max:49',
                'inv_address' => 'nullable|min:2|max:254',
                'company_name' => 'nullable|min:2|max:254',
                'vat_no' => 'nullable|min:2|max:254',
                'vip' => 'nullable|in:A,B,C',
                'res_type' => 'nullable|in:D,M,Y',
                'customerType' => 'required|in:1,2,3,4',
                'scth_transaction_id' => 'nullable',
                'purposeOfVisit' => 'required|in:1,2,3,4,5,6,7,8,9,10',
                //'res_rate_days.*.day_date' => Rule::requiredIf($this->input('res_type') === 'D' || $this->input('res_type') === null || !$this->has('res_type')),
                'res_rate_days.*.day_date' =>'required_if:res_type,D,null|date',
    
                'is_sent_shomoos' => 'nullable|boolean',
                'shomoos_id' => 'nullable',
                'no_of_child' => 'nullable|numeric',
                'no_of_inf' => 'nullable|numeric',
                 //validation of table res_rate_days
                // 'res_rate_days.*.day_date'=> 'nullable|date',
                 'res_rate_days.*.rm_rate'=> 'nullable|numeric',
                 'res_rate_days.*.rate_id'=> 'nullable|exists:rate_codes,id',
                 'res_rate_days.*.meal_id'=> 'nullable|exists:meals,id',
                 'res_rate_days.*.meal_package_id'=> 'nullable|exists:meal_packages,id',
                 
                 'moreNameData.*.profile_id' => 'nullable|exists:guest_profiles,id',
                 'moreNameData.*.country_id' => 'nullable|exists:countries,id',
                 'moreNameData.*.first_name' => 'nullable|min:2|max:50',
                 'moreNameData.*.last_name' => 'nullable|min:2|max:50',
                 'moreNameData.*.mobile' => 'nullable|min:2|max:20',
                 'moreNameData.*.id_no' => 'nullable|min:2|max:30',
                 
            ];
        }
  
    } elseif($this->onlyNameData && !$this->moreNameData) {
        return [
            'file' => 'nullable|mimes:jpeg,png,pdf',

            'folio_no' => 'nullable|min:3|max:99',
            'in_date'=> 'required|date',
            'out_date'=> 'required|date',
            'original_out_date'=> 'nullable|date',
            'no_of_nights'=> 'required|numeric|max:9999999',
            'rm_rate'=> 'required|numeric',
            'taxes'=> 'nullable|numeric',
            'no_of_pax'=> 'required|numeric|max:999999',
            'hotel_note'=> 'nullable',
            'client_note'=> 'nullable',
            'way_of_payment'=> 'required|min:3|max:49',
            'profile_id'=> 'nullable|exists:guest_profiles,id',
            'company_id'=> 'required|exists:company_profiles,id',
            'room_id'=> 'nullable|exists:rooms,id',
            //'room_type_id'=> 'required|exists:room_types,id',
            'room_type_id'=>($this->is_dummy == 1)?'nullable' : 'required|exists:room_types,id',
            'rate_code_id'=> 'nullable|exists:rate_codes,id',
            'market_segment_id'=> 'required|exists:markets,id',
            'source_of_business_id'=> 'required|exists:businesses,id',
            'meal_id'=> 'nullable|exists:meals,id',
            'meal_package_id'=> 'nullable|exists:meal_packages,id',

           // 'created_by'=> 'required|exists:users,id',
            'created_inhousGuest_at'=> 'nullable|date',
            'checked_out'=> 'nullable|boolean',
            'checkout_by'=> 'nullable|exists:users,id',
            'checkout_at'=> 'nullable|date',
            'is_reservation'=> 'nullable|boolean',
            'res_status'=> 'required|in:CNF,NCF,CNC,NSH',
            'is_group'=> 'nullable|boolean',
            'group_code'=> 'nullable|min:3|max:254',
            'is_dummy'=> 'nullable|boolean',
            'Is_PM'=> 'nullable|boolean',
            'res_no' => 'nullable|max:49',
            'ref_no' => 'nullable|max:49',
            'inv_address' => 'nullable|min:2|max:254',
            'company_name' => 'nullable|min:2|max:254',
            'vat_no' => 'nullable|min:2|max:254',
            'res_type' => 'nullable|in:D,M,Y',

            'is_sent_shomoos' => 'nullable|boolean',
            'shomoos_id' => 'nullable',
            'no_of_child' => 'nullable|numeric',
            'no_of_inf' => 'nullable|numeric',
             //validation of table res_rate_days
             'res_rate_days.*.day_date'=> 'nullable|date',
             'res_rate_days.*.rm_rate'=> 'nullable|numeric',
             'res_rate_days.*.rate_id'=> 'nullable|exists:rate_codes,id',
             'res_rate_days.*.meal_id'=> 'nullable|exists:meals,id',
             'res_rate_days.*.meal_package_id'=> 'nullable|exists:meal_packages,id',

              //moreName request of table more_name
              'onlyNameData.*.profile_id' => 'nullable|exists:guest_profiles,id',
              'onlyNameData.*.country_id' => 'nullable|exists:countries,id',
              'onlyNameData.*.first_name' => 'nullable|min:2|max:50',
              'onlyNameData.*.last_name' => 'nullable|min:2|max:50',
              'onlyNameData.*.email' => 'nullable|email|min:2|max:50',
              'onlyNameData.*.mobile' => 'nullable|min:2|max:20',
              'onlyNameData.*.id_no' => 'nullable|min:2|max:30',
        ];
    } elseif($route == 'reservation' && $this->onlyNameData && $this->moreNameData) {
        return [
            'file' => 'nullable|mimes:jpeg,png,pdf',

            'folio_no' => 'nullable|min:3|max:99',
            'in_date'=> 'required|date',
            'out_date'=> 'required|date',
            'original_out_date'=> 'nullable|date',
            'no_of_nights'=> 'required|numeric|max:9999999',
            'rm_rate'=> 'required|numeric',
            'taxes'=> 'nullable|numeric',
            'no_of_pax'=> 'required|numeric|max:999999',
            'hotel_note'=> 'nullable',
            'client_note'=> 'nullable',
            'way_of_payment'=> 'required|min:3|max:49',
            'profile_id'=> 'nullable|exists:guest_profiles,id',
            'company_id'=> 'required|exists:company_profiles,id',
            'room_id'=> 'nullable|exists:rooms,id',
            'room_type_id'=> 'required|exists:room_types,id',
            'rate_code_id'=> 'nullable|exists:rate_codes,id',
            'market_segment_id'=> 'required|exists:markets,id',
            'source_of_business_id'=> 'required|exists:businesses,id',
            'meal_id'=> 'nullable|exists:meals,id',
            'meal_package_id'=> 'nullable|exists:meal_packages,id',

           // 'created_by'=> 'required|exists:users,id',
            'created_inhousGuest_at'=> 'nullable|date',
            'checked_out'=> 'nullable|boolean',
            'checkout_by'=> 'nullable|exists:users,id',
            'checkout_at'=> 'nullable|date',
            'is_reservation'=> 'nullable|boolean',
            'res_status'=> 'required|in:CNF,NCF,CNC,NSH',
            'is_group'=> 'nullable|boolean',
            'group_code'=> 'nullable|min:3|max:254',
            'is_dummy'=> 'nullable|boolean',
            'Is_PM'=> 'nullable|boolean',
            'res_no' => 'nullable|max:49',
            'ref_no' => 'nullable|max:49',
            'inv_address' => 'nullable|min:2|max:254',
            'company_name' => 'nullable|min:2|max:254',
            'vat_no' => 'nullable|min:2|max:254',
            'res_type' => 'nullable|in:D,M,Y',
            'customerType' => 'required|in:1,2,3,4',
            'scth_transaction_id' => 'nullable',
            'purposeOfVisit' => 'required|in:1,2,3,4,5,6,7,8,9,10',

            'is_sent_shomoos' => 'nullable|boolean',
            'shomoos_id' => 'nullable',
            'no_of_child' => 'nullable|numeric',
            'no_of_inf' => 'nullable|numeric',
             //validation of table res_rate_days
             'res_rate_days.*.day_date'=> 'nullable|date',
             'res_rate_days.*.rm_rate'=> 'nullable|numeric',
             'res_rate_days.*.rate_id'=> 'nullable|exists:rate_codes,id',
             'res_rate_days.*.meal_id'=> 'nullable|exists:meals,id',
             'res_rate_days.*.meal_package_id'=> 'nullable|exists:meal_packages,id',

              //moreName request of table more_name
              'onlyNameData.*.profile_id' => 'nullable|exists:guest_profiles,id',
              'onlyNameData.*.country_id' => 'nullable|exists:countries,id',
              'onlyNameData.*.first_name' => 'nullable|min:2|max:50',
              'onlyNameData.*.last_name' => 'nullable|min:2|max:50',
              'onlyNameData.*.email' => 'nullable|email|min:2|max:50',
              'onlyNameData.*.mobile' => 'nullable|min:2|max:20',
              'onlyNameData.*.id_no' => 'nullable|min:2|max:30',

              'moreName.*.profile_id' => 'nullable|exists:guest_profiles,id',
              'moreName.*.country_id' => 'nullable|exists:countries,id',
              'moreName.*.first_name' => 'nullable|min:2|max:50',
              'moreName.*.last_name' => 'nullable|min:2|max:50',
              'moreName.*.mobile' => 'nullable|min:2|max:20',
              'moreName.*.id_no' => 'nullable|min:2|max:30',
        ];
    }elseif($route == 'storeGroupReservision' || $route=='updateGroupGuest'){
        //validation of guest group profile
        if($route=='updateGroupGuest')
        {
            return [
                'groupGuest.*.guest_id' => 'nullable|exists:guests,id',
                'groupGuest.*.folio_no' => 'nullable|min:3|max:99',
                'groupGuest.*.in_date'=> 'nullable|date',
                'groupGuest.*.out_date'=> 'nullable|date',
                'groupGuest.*.original_out_date'=> 'nullable|date',
                'groupGuest.*.no_of_nights'=> 'nullable|numeric|max:9999999',
                'groupGuest.*.rm_rate'=> 'nullable|numeric',
                'groupGuest.*.taxes'=> 'nullable|numeric',
                'groupGuest.*.no_of_pax'=> 'nullable|numeric|max:999999',
                'groupGuest.*.hotel_note'=> 'nullable',
                'groupGuest.*.client_note'=> 'nullable',
                'groupGuest.*.way_of_payment'=> 'nullable|min:3|max:49',
                'groupGuest.*.profile_id'=> 'nullable|exists:guest_profiles,id',
                'groupGuest.*.company_id'=> 'nullable|exists:company_profiles,id',
                'groupGuest.*.room_id'=> 'nullable|exists:rooms,id',
               // 'room_type_id'=> 'required|exists:room_types,id',
                'groupGuest.*.rate_code_id'=> 'nullable|exists:rate_codes,id',
                'groupGuest.*.market_segment_id'=> 'nullable|exists:markets,id',
                'groupGuest.*.source_of_business_id'=> 'nullable|exists:businesses,id',
                'groupGuest.*.meal_id'=> 'nullable|exists:meals,id',
                'groupGuest.*.meal_package_id'=> 'nullable|exists:meal_packages,id',
        
               // 'created_by'=> 'required|exists:users,id',
                'groupGuest.*.created_inhousGuest_at'=> 'nullable|date',
                'groupGuest.*.checked_out'=> 'nullable|boolean',
                'groupGuest.*.checkout_by'=> 'nullable|exists:users,id',
                'groupGuest.*.checkout_at'=> 'nullable|date',
                'groupGuest.*.is_reservation'=> 'nullable|boolean',
                'groupGuest.*.res_status'=> 'nullable|in:CNF,NCF,CNC,NSH',
                'groupGuest.*.is_group'=> 'nullable|boolean',
                'groupGuest.*.group_code'=> 'required|min:1|max:254|exists:guests,group_code',
                'groupGuest.*.is_dummy'=> 'nullable|boolean',
                'groupGuest.*.Is_PM'=> 'nullable|boolean',
                'groupGuest.*.res_no' => 'nullable|max:49',
                'groupGuest.*.ref_no' => 'nullable|max:49',
                'groupGuest.*.inv_address' => 'nullable|min:2|max:254',
                'groupGuest.*.company_name' => 'nullable|min:2|max:254',
                'groupGuest.*.vat_no' => 'nullable|min:2|max:254',
                'groupGuest.*.updated' => 'required',
                'groupGuest.*.room_type_id' => 'required||exists:room_types,id',
        
                  'groupGuest.*.roomType' => 'nullable|exists:room_types,id',
                  //'rooms.*.rate_code_id' => 'nullable|exists:rate_codes,id',
                  'groupGuest.*.room_id.*' => 'nullable|exists:rooms,id',
                  
                  'groupGuest.*.customerType' => 'nullable|in:1,2,3,4',
                  'groupGuest.*.scth_transaction_id' => 'nullable',
                  'groupGuest.*.purposeOfVisit' => 'nullable|in:1,2,3,4,5,6,7,8,9,10',
            ];
        }else{
            //store group reservatioon
    return [
        'folio_no' => 'nullable|min:3|max:99',
        'in_date'=> 'required|date',
        'out_date'=> 'required|date',
        'original_out_date'=> 'nullable|date',
        'no_of_nights'=> 'required|numeric|max:9999999',
        'rm_rate'=> 'nullable|numeric',
        'taxes'=> 'nullable|numeric',
        'no_of_pax'=> 'nullable|numeric|max:999999',
        'hotel_note'=> 'nullable',
        'client_note'=> 'nullable',
        'way_of_payment'=> 'required|min:3|max:49',
        'profile_id'=> 'required|exists:guest_profiles,id',
        'company_id'=> 'required|exists:company_profiles,id',
        'room_id'=> 'nullable|exists:rooms,id',
       // 'room_type_id'=> 'required|exists:room_types,id',
        'rate_code_id'=> 'nullable|exists:rate_codes,id',
        'market_segment_id'=> 'required|exists:markets,id',
        'source_of_business_id'=> 'required|exists:businesses,id',
        'meal_id'=> 'nullable|exists:meals,id',
        'meal_package_id'=> 'nullable|exists:meal_packages,id',

       // 'created_by'=> 'required|exists:users,id',
        'created_inhousGuest_at'=> 'nullable|date',
        'checked_out'=> 'nullable|boolean',
        'checkout_by'=> 'nullable|exists:users,id',
        'checkout_at'=> 'nullable|date',
        'is_reservation'=> 'nullable|boolean',
        'res_status'=> 'required|in:CNF,NCF,CNC,NSH',
        'is_group'=> 'nullable|boolean',
        'group_code'=> 'nullable|min:3|max:254',
        'is_dummy'=> 'nullable|boolean',
        'Is_PM'=> 'nullable|boolean',
        'res_no' => 'nullable|max:49',
        'ref_no' => 'nullable|max:49',
        'inv_address' => 'nullable|min:2|max:254',
        'company_name' => 'nullable|min:2|max:254',
        'vat_no' => 'nullable|min:2|max:254',
        'customerType' => 'required|in:1,2,3,4',
        'scth_transaction_id' => 'nullable',
        'purposeOfVisit' => 'required|in:1,2,3,4,5,6,7,8,9,10',

        'is_sent_shomoos' => 'nullable|boolean',
        'shomoos_id' => 'nullable',
        'no_of_child' => 'nullable|numeric',
        'no_of_inf' => 'nullable|numeric',

          'rooms.*.roomType' => 'required|exists:room_types,id',
          'rooms.*.rm_rate' => 'required',
          'rooms.*.no_pax' => 'required|numeric',
          'rooms.*.rate_code_id' => 'nullable|exists:rate_codes,id',
          'rooms.*.room_id.*' => 'required|exists:rooms,id',
    ];
}
    
    
    }elseif((in_array($this->method(), ['PUT', 'PATCH'])) && $updateRoute == 'reservation'){
        return [
            'folio_no' => 'nullable|min:3|max:99',
            'in_date'=> 'nullable|date',
            'out_date'=> 'nullable|date',
            'original_out_date'=> 'nullable|date',
            'no_of_nights'=> 'nullable|numeric|max:9999999',
            'rm_rate'=> 'nullable|numeric',
            'taxes'=> 'nullable|numeric',
            'no_of_pax'=> 'nullable|numeric|max:999999',
            'hotel_note'=> 'nullable',
            'client_note'=> 'nullable',
            'way_of_payment'=> 'nullable|min:3|max:49',
            'profile_id'=> 'nullable|exists:guest_profiles,id',
            'company_id'=> 'nullable|exists:company_profiles,id',
            'room_id'=> 'nullable|exists:rooms,id',
            'room_type_id'=> 'nullable|exists:room_types,id',
            'rate_code_id'=> 'nullable|exists:rate_codes,id',
            'market_segment_id'=> 'nullable|exists:markets,id',
            'source_of_business_id'=> 'nullable|exists:businesses,id',
            'meal_id'=> 'nullable|exists:meals,id',
            'meal_package_id'=> 'nullable|exists:meal_packages,id',
            'res_type' => 'nullable|in:D,M,Y',

           // 'created_by'=> 'required|exists:users,id',
            'created_inhousGuest_at'=> 'nullable|date',
            'checked_out'=> 'nullable|boolean',
            'checkout_by'=> 'nullable|exists:users,id',
            'checkout_at'=> 'nullable|date',
            'is_reservation'=> 'nullable|boolean',
            'res_status'=> 'nullable|in:CNF,NCF,CNC,NSH',
            'is_group'=> 'nullable|boolean',
            'group_code'=> 'nullable|min:3|max:254',
            'is_dummy'=> 'nullable|boolean',
            'Is_PM'=> 'nullable|boolean',
            'res_no' => 'nullable|max:49',
            'ref_no' => 'nullable|max:49',
            'inv_address' => 'nullable|min:2|max:254',
            'company_name' => 'nullable|min:2|max:254',
            'vat_no' => 'nullable|min:2|max:254',
            'customerType' => 'required|in:1,2,3,4',
            'scth_transaction_id' => 'nullable',
            'purposeOfVisit' => 'required|in:1,2,3,4,5,6,7,8,9,10',
             //validation of table res_rate_days
             'is_sent_shomoos' => 'nullable|boolean',
             'shomoos_id' => 'nullable',
             'no_of_child' => 'nullable|numeric',
             'no_of_inf' => 'nullable|numeric',
             
             'res_rate_days'=> 'nullable|array',
             'res_rate_days.*.day_date'=> 'nullable|date',
             'res_rate_days.*.rm_rate'=> 'nullable|numeric',
             'res_rate_days.*.rate_id'=> 'nullable|exists:rate_codes,id',
             'res_rate_days.*.meal_id'=> 'nullable|exists:meals,id',
             'res_rate_days.*.meal_package_id'=> 'nullable|exists:meal_packages,id',
             
        ];
    }elseif($updateRoute == 'lockedReservision'){
        return[
            'lock' => 'required|boolean'
        ];
    }
}
}
