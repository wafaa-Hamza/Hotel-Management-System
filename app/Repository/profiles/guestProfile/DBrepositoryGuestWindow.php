<?php
namespace App\Repository\profiles\guestProfile;

use App\Http\Requests\GuestProfileRequest;
use App\Models\guest_profile;
use App\Models\Window;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryWindowInterface;

class DBrepositoryGuestWindow implements RepositoryWindowInterface{
    private $windowModel; 
    private $guestModel; 
private $validation;
    public function __construct(Window $windowModel,guest_profile $guestModel)
    {
        $this->windowModel = $windowModel;
        $this->guestModel = $guestModel;
    }

public function show($id)
{  
    $window = $this->windowModel::where('guest_id', $id)->with('guest')->get();
        return $window;
    
}

public function store($request,$guest_id=null,$windowID)
{
    $windowData = $this->windowModel::create([
       // dd( $request['guest_id']),
        'window_number' => (empty($request['window_number']))? $windowID+1 : 1,

        'window_name' => $request['window_name'],

        'invoice_number' => (!empty($request['invoice_number']))? $request['invoice_number'] : null,

        'guest_id' => (is_null($request['guest_id'])) ? $guest_id['guest_id'] : $request['guest_id'],

    ]);


    

    // $this->windowModel->window_number = (empty($request['window_number']))? $windowID+1 : 1;
    // $this->windowModel->window_name = $request['window_name'];
    // //$this->windowModel->invoice_number = $request['invoice_number'];
    // $this->windowModel->invoice_number =(!empty($request['invoice_number']))? $request['invoice_number'] : null;

    // if($guest_id == null){
        
    //     $this->windowModel->guest_id = $request['guest_id'];

    // }else{

    //     $this->windowModel->guest_id = $guest_id['guest_id'];
    // }
    //  $this->windowModel->save();
    
   if($guest_id == null)
   {
    $guestInhouse =  $this->windowModel::where('guest_id',$request['guest_id'])->with('guest')->get();
    return  $guestInhouse;

   }else{
    $guestInhouse =  $this->guestModel::where('id',$guest_id['guest_id'])->get();

    return  $guestInhouse;
   }

}
// public function update($request, $id)
// {
   
//     $guestProfile = $this->guestProfileModel::where('id_no', $id)->first();
//     if($guestProfile){
//         $this->guestProfileModel::where('id_no',$id)->Update([
//             'Profile_no'  => (!empty($request['Profile_no'])) ? $request['Profile_no'] :  $guestProfile->Profile_no,
//             'first_name'  => (!empty($request['first_name'])) ? $request['first_name'] :  $guestProfile->first_name,
//             'last_name'  => (!empty($request['last_name'])) ? $request['last_name'] :  $guestProfile->last_name,
//             'id_no'  => (!empty($request['id_no'])) ? $request['id_no'] :  $guestProfile->id_no,
//             'mobile'  => (!empty($request['mobile'])) ? $request['mobile'] :  $guestProfile->mobile,
//             'phone'  => (!empty($request['phone'])) ? $request['phone'] :  $guestProfile->phone,
//             'email'  => (!empty($request['email'])) ? $request['email'] :  $guestProfile->email,
//             'address'  => (!empty($request['address'])) ? $request['address'] :  $guestProfile->address,
//             'country_id'  => (!empty($request['country_id'])) ? $request['country_id'] :  $guestProfile->country_id,
//             'city'  => (!empty($request['city'])) ? $request['city'] :  $guestProfile->city,
//             'date_of_birth'  => (!empty($request['date_of_birth'])) ? $request['date_of_birth'] :  $guestProfile->date_of_birth,
//             'gender'  => (!empty($request['gender'])) ? $request['gender'] :  $guestProfile->gender,
//             'created_by'  => (!empty($request['created_by'])) ? $request['created_by'] :  $guestProfile->created_by,
               
//         ]);
//         DB::connection('landlord')->table('guest_profiles')->where('id_no',$id)->update([
//             'Profile_no'  => (!empty($request['Profile_no'])) ? $request['Profile_no'] :  $guestProfile->Profile_no,
//             'first_name'  => (!empty($request['first_name'])) ? $request['first_name'] :  $guestProfile->first_name,
//             'last_name'  => (!empty($request['last_name'])) ? $request['last_name'] :  $guestProfile->last_name,
//             'id_no'  => (!empty($request['id_no'])) ? $request['id_no'] :  $guestProfile->id_no,
//             'mobile'  => (!empty($request['mobile'])) ? $request['mobile'] :  $guestProfile->mobile,
//             'phone'  => (!empty($request['phone'])) ? $request['phone'] :  $guestProfile->phone,
//             'email'  => (!empty($request['email'])) ? $request['email'] :  $guestProfile->email,
//             'address'  => (!empty($request['address'])) ? $request['address'] :  $guestProfile->address,
//             'country_id'  => (!empty($request['country_id'])) ? $request['country_id'] :  $guestProfile->country_id,
//             'city'  => (!empty($request['city'])) ? $request['city'] :  $guestProfile->city,
//             'date_of_birth'  => (!empty($request['date_of_birth'])) ? $request['date_of_birth'] :  $guestProfile->date_of_birth,
//             'gender'  => (!empty($request['gender'])) ? $request['gender'] :  $guestProfile->gender,
//             'created_by'  => (!empty($request['created_by'])) ? $request['created_by'] :  $guestProfile->created_by,
//         ]);
//         $guestProfile = $this->guestProfileModel::where('id_no',$id)->get();
//         return  $guestProfile;
//     }else{
//         return null;
//     }       
// }



}
