<?php

namespace App\Repository\profiles\guestProfile;

use App\Models\guest_profile;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\GuestProfileRequest;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryGuestProfileInterface;
use App\Models\Setting;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\DBAL\TimestampType;
use Illuminate\Support\Facades\Auth;

class DBrepositoryGuestPrpfile implements RepositoryGuestProfileInterface
{
    private $guestProfileModel;
    private $validation;
    private $settingModel;
    public function __construct(guest_profile $guestProfileModel, Setting $settingModel)
    {
        $this->guestProfileModel = $guestProfileModel;
        $this->settingModel = $settingModel;
    }

    public function index()
    {
        $guestProfile = $this->guestProfileModel->with('country')->get();
        return $guestProfile;
    }

    public function seachProfile($request)
    {
        $profileData = $this->guestProfileModel::search($request->term, null, $request->mixedSearch, $request->spaceficSearch)->get();
        return ($profileData);
    }
    public function profilePagination()
    {
        $guestProfile = $this->guestProfileModel->with('country', 'user', 'user_updated')->paginate(request()->segment(count(request()->segments())));
        return $guestProfile;
    }
    public function store($request)
    {
        $time = Carbon::now();
         $tenantID = DB::connection('landlord')->table('tenants')->where('domain', request()->host())->first();
        // $maxProfile = DB::connection('tenant')->table('guest_profiles')
        //     ->select(DB::raw('MAX(CAST(Profile_no AS UNSIGNED)) AS max_value'))
        //     ->value('max_value');

        // if ($maxProfile == null || $maxProfile == 0) {
        //     $maxProfile = 0;
        // }
        // $numberOfTenantID = sprintf("%03d", $tenantID->id);

        // DB::connection('landlord')->table('guest_profiles')->where('domain', request()->host())->first();


        $maxProfile = DB::connection('tenant')->table('guest_profiles')
            ->select(DB::raw('MAX(CAST(Profile_no AS UNSIGNED)) +1 AS max_value'))
            ->value('max_value');

        if ($maxProfile == null || $maxProfile == 0) {
            $maxProfile = 9661;
        }

        // $profileNumber =  $maxProfile;
        // $numberOfTenantID = sprintf("%07d", $profileNumber);
        $profil_no =  $maxProfile+1;
       // dd($profil_no);
        //dd($maxProfile, $profileNumber, $numberOfTenantID, $tenantID->id);

        $tenantGuestID = DB::connection('tenant')->table('guest_profiles')->InsertGetId([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'id_no' => $request->id_no,
            'Profile_no' =>$profil_no,
            'mobile' => $request->mobile,
            'phone' => (!empty($request->phone))  ? $request->phone : null,
            'email' => (!empty($request->email))  ? $request->email : null,
            'address' => (!empty($request->address))  ? $request->address : null,
            'city' => (!empty($request->city))  ? $request->city : null,
            'language' => (!empty($request->language))  ? $request->language : null,
            'date_of_birth' => (!empty($request->date_of_birth))  ? $request->date_of_birth : null,
            'gender' => $request->gender,
            'version_no' => $request->version_no,
            'id_type_id' => $request->id_type_id,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'country_id' => $request->country_id,
            'created_at' => $time,

        ]);
        if ($tenantGuestID) {

            $landlordGuestID = DB::connection('landlord')->table('guest_profiles')->where('id_no', $request->id_no)->first();
            if (!$landlordGuestID) {
                $tenantCreatedData = DB::connection('tenant')->table('guest_profiles')->where('id_no', $request->id_no)
                    ->join('users', 'guest_profiles.created_by', '=', 'users.id')->select('users.firstname', 'users.lastname', 'guest_profiles.id')
                    ->first();

                $numberOfTenantID = sprintf("%03d", $tenantID->id);
                $profileNumber = $numberOfTenantID . $tenantCreatedData->id;
                $hotelName = $this->settingModel->first();

                $landlordGuestID = DB::connection('landlord')->table('guest_profiles')->insert([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'id_no' => $request->id_no,
                    'Profile_no' => $profileNumber,
                    'mobile' => $request->mobile,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'address' => $request->address,
                    'city' => $request->city,
                    'language' => $request->language,
                    'date_of_birth' => $request->date_of_birth,
                    'gender' => $request->gender,
                    'created_by' => $tenantCreatedData->firstname . ' ' . $tenantCreatedData->lastname . '-' . $hotelName->name,
                    'updated_by' => $tenantCreatedData->firstname . ' ' . $tenantCreatedData->lastname . '-' . $hotelName->name,
                    'country_id' => $request->country_id,
                    'created_at' => $time,
                    'version_no' => $request->version_no,
                    'id_type_id' => $request->id_type_id,
                ]);

                $update_profile_nimber = DB::connection('tenant')->table('guest_profiles')->where('id_no', $request->id_no)
                    ->update(['Profile_no' => $profileNumber]);
            } else {
                $update_profile_nimber = DB::connection('tenant')->table('guest_profiles')->where('id_no', $request->id_no)
                    ->update(['Profile_no' => $landlordGuestID->Profile_no]);
            }
        }
        $gusetProfile =  $this->guestProfileModel::where('id_no', $request->id_no)->with('user', 'user_updated', 'country')->get();

        return $gusetProfile;
    }

    public function show($id)
    {
        //$tenantID =  DB::connection('tenant')->host();
        $guestProfile = $this->guestProfileModel::where('Profile_no', $id)->with('country', 'user', 'user_updated')->first();
        if (!$guestProfile) {
            $guestProfile = DB::connection('landlord')->table('guest_profiles')->where('Profile_no', $id)->select()->first();
        }
        return $guestProfile;
    }
    public function update($request, $id)
    {

        $time = Carbon::now();
        $guestProfile = $this->guestProfileModel::where('Profile_no', $id)->first();
        $checkIDNOUnique = $this->guestProfileModel::where('id', '!=', $guestProfile->id)->where('id_no', $request->id_no)->orWhere('email', $request->email)->where('id', '!=', $guestProfile->id)->first();
        if (!$checkIDNOUnique) {
            if ($guestProfile) {
                $hostDomain = request()->host();
                $hotelName = $this->settingModel->first();
                $tenantCreatedData = DB::connection('tenant')->table('guest_profiles')->where('Profile_no', $id)
                    ->join('users', 'guest_profiles.created_by', '=', 'users.id')->select('users.firstname', 'users.lastname', 'guest_profiles.id')->first();
                $this->guestProfileModel::where('Profile_no', $id)->Update([
                    //'Profile_no'  => (!empty($request['Profile_no'])) ? $request['Profile_no'] :  $guestProfile->Profile_no,
                    'first_name'  => (!empty($request['first_name'])) ? $request['first_name'] :  $guestProfile->first_name,
                    'last_name'  => (!empty($request['last_name'])) ? $request['last_name'] :  $guestProfile->last_name,
                    'id_no'  => (!empty($request['id_no'])) ? $request['id_no'] :  $guestProfile->id_no,
                    'mobile'  => (!empty($request['mobile'])) ? $request['mobile'] :  $guestProfile->mobile,
                    'phone'  => (!empty($request['phone'])) ? $request['phone'] :  $guestProfile->phone,
                    'email'  => (!empty($request['email'])) ? $request['email'] :  $guestProfile->email,
                    'address'  => (!empty($request['address'])) ? $request['address'] :  $guestProfile->address,
                    'country_id'  => (!empty($request['country_id'])) ? $request['country_id'] :  $guestProfile->country_id,
                    'city'  => (!empty($request['city'])) ? $request['city'] :  $guestProfile->city,
                    'date_of_birth'  => (!empty($request['date_of_birth'])) ? $request['date_of_birth'] :  $guestProfile->date_of_birth,
                    'gender'  => (!empty($request['gender'])) ? $request['gender'] :  $guestProfile->gender,
                    'version_no'  => (!empty($request['version_no'])) ? $request['version_no'] :  $guestProfile->version_no,
                    'id_type_id'  => (!empty($request['id_type_id'])) ? $request['id_type_id'] :  $guestProfile->id_type_id,
                    'updated_by' => auth()->user()->id,
                    'updated_at' => $time,



                ]);
                // dd( $tenantCreatedData);
                DB::connection('landlord')->table('guest_profiles')->where('Profile_no', $id)->update([

                    // 'Profile_no'  => (!empty($request['Profile_no'])) ? $request['Profile_no'] :  $guestProfile->Profile_no,
                    'first_name'  => (!empty($request['first_name'])) ? $request['first_name'] :  $guestProfile->first_name,
                    'last_name'  => (!empty($request['last_name'])) ? $request['last_name'] :  $guestProfile->last_name,
                    'id_no'  => (!empty($request['id_no'])) ? $request['id_no'] :  $guestProfile->id_no,
                    'mobile'  => (!empty($request['mobile'])) ? $request['mobile'] :  $guestProfile->mobile,
                    'phone'  => (!empty($request['phone'])) ? $request['phone'] :  $guestProfile->phone,
                    'email'  => (!empty($request['email'])) ? $request['email'] :  $guestProfile->email,
                    'address'  => (!empty($request['address'])) ? $request['address'] :  $guestProfile->address,
                    'country_id'  => (!empty($request['country_id'])) ? $request['country_id'] :  $guestProfile->country_id,
                    'city'  => (!empty($request['city'])) ? $request['city'] :  $guestProfile->city,
                    'date_of_birth'  => (!empty($request['date_of_birth'])) ? $request['date_of_birth'] :  $guestProfile->date_of_birth,
                    'updated_by' => $tenantCreatedData->firstname . ' ' . $tenantCreatedData->lastname . '-' . $hotelName->name,
                    'version_no'  => (!empty($request['version_no'])) ? $request['version_no'] :  $guestProfile->version_no,
                    'id_type_id'  => (!empty($request['id_type_id'])) ? $request['id_type_id'] :  $guestProfile->id_type_id,
                    'updated_at' => $time,

                    'gender'  => (!empty($request['gender'])) ? $request['gender'] :  $guestProfile->gender,

                ]);
                $guestProfile = $this->guestProfileModel::where('Profile_no', $id)->with('user', 'user_updated', 'country')->get();
                return  $guestProfile;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function guest_profile_search($request)
    {
        $guests =  $this->guestProfileModel::where(function ($q) use ($request) {
            if ($request->mobile == null) {
                $q->where('id_no', 'like', '%' . $request->id_no . '%');
            } elseif ($request->mobile && $request->id_no) {
                $q->where(function ($q) use ($request) {
                    $q->where('mobile', 'like', '%' . $request->mobile . '%')
                        ->orWhere('id_no', 'like', '%' . $request->id_no . '%');
                });

                $q->orWhere(function ($q) use ($request) {
                    $q->where('phone', 'like', '%' . $request->mobile . '%')
                        ->orWhere('id_no', 'like', '%' . $request->id_no . '%');
                });
            } else {
                $q->where('mobile', 'like', '%' . $request->mobile . '%')
                    ->orWhere('phone', 'like', '%' . $request->mobile . '%');
            }
        })
            // where('mobile', 'like', '%' . $request->mobile . '%')
            // ->where('id_no', 'like', '%' . $request->id_no . '%')
            // ->orWhere('phone','like','%' . $request->mobile . '%')
            // ->where('id_no', 'like', '%' . $request->id_no . '%')
            ->get();

        if (!$guests->first()) {
            $guests = DB::connection('landlord')->table('guest_profiles')->
                //     ->where('mobile', 'like', '%' . $request->mobile . '%')
                //    ->where('id_no', 'like', '%' . $request->id_no . '%')
                //    ->orWhere('phone','like','%' . $request->mobile . '%')
                //    ->where('id_no', 'like', '%' . $request->id_no . '%')

                where(function ($q) use ($request) {
                    if ($request->mobile == null) {
                        $q->where('id_no', 'like', '%' . $request->id_no . '%');
                    } elseif ($request->mobile && $request->id_no) {
                        $q->where(function ($q) use ($request) {
                            $q->where('mobile', 'like', '%' . $request->mobile . '%')
                                ->orWhere('id_no', 'like', '%' . $request->id_no . '%');
                        });

                        $q->orWhere(function ($q) use ($request) {
                            $q->where('phone', 'like', '%' . $request->mobile . '%')
                                ->where('id_no', 'like', '%' . $request->id_no . '%');
                        });
                    } else {
                        $q->where('mobile', 'like', '%' . $request->mobile . '%')
                            ->orWhere('phone', 'like', '%' . $request->mobile . '%');
                    }
                })
                ->get();
        }
        return $guests;
    }

    public function store_from_landlord($profile_no)
    {

        $time = Carbon::now();
        $landlordGuest = DB::connection('landlord')->table('guest_profiles')->where('Profile_no', $profile_no)->first();
        $tenantGuest = DB::connection('tenant')->table('guest_profiles')->where('Profile_no', $profile_no)->first();
        // dd( $profile_no);
        if ($landlordGuest != null && $tenantGuest == null) {
            // dd('ss');
            $tenantGuestID = DB::connection('tenant')->table('guest_profiles')->InsertGetId([
                'first_name' => $landlordGuest->first_name,
                'last_name' => $landlordGuest->last_name,
                'id_no' => $landlordGuest->id_no,
                'Profile_no' => $landlordGuest->Profile_no,
                'mobile' => $landlordGuest->mobile,
                'phone' => $landlordGuest->phone,
                'version_no' => $landlordGuest->version_no,
                'email' => $landlordGuest->email,
                'address' => $landlordGuest->address,
                'city' => $landlordGuest->city,
                'language' => $landlordGuest->language,
                'date_of_birth' => $landlordGuest->date_of_birth,
                'gender' => $landlordGuest->gender,
                'version_no' => $landlordGuest->version_no,
                'id_type_id' => $landlordGuest->id_type_id,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'country_id' => $landlordGuest->country_id,
                'created_at' => $time,
            ]);
            $tenantGuest = DB::connection('tenant')->table('guest_profiles')->where('Profile_no', $profile_no)->first();

            return $tenantGuest;
        } else {
            return $tenantGuest;
        }
    }


    public function store_to_landlord($request)
    {
        $time = Carbon::now();

        // dd( $tenantGuestID);


        $landlordGuestID = DB::connection('landlord')->table('guest_profiles')->where('id_no', $request->id_no)->first();
        $maxProfile = DB::connection('landlord')->table('guest_profiles')
            ->select(DB::raw('MAX(CAST(Profile_no AS UNSIGNED)) AS max_value'))
            ->value('max_value');
        //$maxValue = DB::connection('landlord')->table('guest_profiles')->query('MAX(CAST(Profile_no AS UNSIGNED))')->get();

        if ($maxProfile == null) {
            $maxProfile = 0000;
        }

        if (!$landlordGuestID) {
            $maxProfile = substr($maxProfile, 1);

            $numberOfTenantID = sprintf("%03d", 1);
            $profileNumber = $numberOfTenantID . $maxProfile + 1;

            $hotelName = 'MASASOFT';

            $landlordGuestID = DB::connection('landlord')->table('guest_profiles')->insert([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'id_no' => $request->id_no,
                'Profile_no' => $profileNumber,
                'mobile' => $request->mobile,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'language' => $request->language,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'created_by' =>  $hotelName,
                'updated_by' => $hotelName,
                'country_id' => $request->country_id,
                'version_no' => $request->version_no,
                'id_type_id' => $request->id_type_id,
                'created_at' => $time,
            ]);
        } else {
            return 'exist';
        }

        //$gusetProfile =  $this->guestProfileModel::where('id_no', $request->id_no)->with('user', 'user_updated', 'country')->get();

        return true;
    }
    //public function 
    // public function destroy($id)
    // {
    //     $room=$this->roomModel::find($id);
    //     if($room){
    //       $room->delete();
    //         return $room;
    //     }else{
    //         return null;
    //     }
    // }
    // public function geSoftDeletedData()
    // {
    //    $roomsTrashed = $this->roomModel::onlyTrashed()->get();
    //    if($roomsTrashed){
    //         return $roomsTrashed;
    //    }else{
    //         return null;
    //    }


    // }
    // public function restorDataTrashed($id)
    // {
    //     $roomsTrashed = $this->roomModel::where('id', $id)->onlyTrashed()->get();
    //     if ($roomsTrashed) {
    //         $roomRestored = $this->roomModel::withTrashed()->where('id', $id)->restore();
    //         return $roomsTrashed;
    //     } else {
    //         return null;
    //     }
    // }


}
