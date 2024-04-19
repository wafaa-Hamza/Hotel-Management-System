<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Cashier;
use App\Models\Setting;
use App\Models\Language;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\LoginNotification;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        //        $this->authorize('create-user');

        try {
            $validateUser = Validator::make($request->all(), [
                'firstname' => 'required|max:20',
                'lastname' => 'required|max:20',
                'phonenumber' => 'required|numeric|unique:users',
                'role' => 'required|exists:roles,id',
                'email' => 'email|unique:users',
                'password' => 'required|min:6',
                'language' => 'default:en',
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors(),
                ], 401);
            }


            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'phonenumber' => $request->phonenumber,
                'role' => $request->role,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                // 'language' => $request->language,
            ]);


            // $userRole = Role::where('name', $request->role)->with('permissions')->first();
            // $userRole =  $user->syncPermissions($userRole->permissions);
            // Create permissions
            $userRoles = Role::where('id', $request->role)->with('permissions')->first();


            $userRole = Role::where('id', $request->role)->with('permissions')->first();
            $userRole =  $user->syncPermissions($userRole->permissions);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {

        $permissionArr = [];
        $allPermission = [];
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'email|required_without:phonenumber',
                    'phonenumber' => 'numeric|required_without:email',
                    'password' => 'required',
                ]
            );


            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors(),
                ], 401);
            }

            if (is_numeric($request->phonenumber)) {
                if (!Auth::attempt($request->only(['phonenumber', 'password']))) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Phone number & Password does not match with our record.',
                    ], 401);
                }
                $user = User::where('phonenumber', $request->phonenumber)->first();
            } else {

                if (!Auth::attempt($request->only(['email', 'password']))) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Email & Password does not match with our record.',
                    ], 401);
                }
                $user = User::where('email', $request->email)->first();
            }
            // $tenant = tenant::find(1);
            // $tenant->notify(new LoginNotification()); 

            auth()->login($user);

            activity()
                ->causedBy($user)
                ->withProperties(['attributes' => $user])
                ->log('User Successfully Logged In');

            $settings = Setting::get();



            //$user->notify(new LoginNotification());
            //    $tenant = tenant::find(1);
            //     $tenant->notify(new LoginNotification()); 



            //$user->notify(new LoginNotification());
            //    $tenant = tenant::find(1);
            //     $tenant->notify(new LoginNotification()); 

            $state1  = 0;

            $cashierStatus = Cashier::where('user_id', auth()->user()->id)
                ->orderBy('open', 'desc')->first('status');
            // return $cashierStatus;
            if ($cashierStatus != null) {
                if ($cashierStatus->status == 0) {
                    $state1 = 0;
                } else {
                    $state1 = 1;
                }
            }
            $userPermissions = Permission::
                //selectRaw("'*', DB::raw('explode('-', name) as my_values')")
                whereHas('users', function ($q) use ($user) {
                    $q->where('model_id', $user->id);
                })
                ->get();
            foreach ($userPermissions as $permission) {
                $permissionExploded = explode('-', $permission->name);
                $permissionArr['action'] = $permissionExploded[0];
                $permissionArr['subject'] = $permissionExploded[1];
                // dd($permissionArr);
                array_push($allPermission, $permissionArr);
            }

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                //'userAbilities' => $userPermissions,
                'userAbilities' => $allPermission,
                'user' =>  $user,
                'Cashier_Status' => $state1,
                'Settings' => $settings,

            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
    public function logout(Request $request)
    {
        //  return $request;
        $user = $request->user();
        $accessToken = $user->currentAccessToken();
        $accessToken->delete();
        return response()->json([
            'message' => 'User Logged out Successfully',

        ]);
    }

    public function user()
    {
        return response()->json(User::all());
    }
}
