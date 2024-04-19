<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Notifications\NewUserNotification;
use Illuminate\Support\Facades\Notification;

class UserController extends Controller
{
    public function index()
    {
        //        $this->authorize('view-user');

        return response()->json(User::latest()->with('role')->get());


     }

    public function userPagination($id)
    {
        //        $this->authorize('view-user');

        return response()->json(User::latest()->paginate(request()->segment(count(request()->segments()))));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'firstname' => 'required|max:20',
                'lastname' => 'required|max:20',
                'phonenumber' => 'required|numeric|unique:users',
                'email' => 'email|unique:users',
                'password' => 'required|min:6',
                //                'role' => 'required',
                'language' =>  'in:ar,en',
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors(),
                ], 401);
            }

            //  $this->authorize('create-user');


            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'phonenumber' => $request->phonenumber,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'language' => $request->language,
            ]);
            $userRoles = Role::where('id', $request->role)->with('permissions')->first();

            $user->syncPermissions([$userRoles->permissions]);

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

    public function Notifications()
    {
        //        $this->authorize('create-notification');

        if (auth()->user()) {
            $user = User::first();
            auth()->user()->notify(new NewUserNotification($user));
        }
        //dd('Done');
        $notification = DB::table('notifications')->whereJsonContains('data->sent_to', 1)->get();


        return $notification;
    }

    public function deleteNotification($id)
    {
        //        $this->authorize('delete-notification');

        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->delete();
        }
        return response()->json('Notifications Deleted');
    }



    public function MarkAsRead($id)
    {
        //        $this->authorize('markread-notification');

        if ($id) {
            auth()->user()->unreadNotifications->where('id', $id)->markAsRead();
        }
        return 'done';
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {

        $userShow = user::with('role')->where('id', $user->id)
            ->get();
        return $userShow;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'firstname' => 'required|max:20',
                'lastname' => 'required|max:20',
                'phonenumber' => ['required', 'numeric', Rule::unique('users')->ignore($user->id)],
                'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
                'role' => 'required',
                'language' => 'in:ar,en|nullable',
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors(),
                ], 401);
            }
            //            $this->authorize('edit-user');
            $user = User::findOrFail($user->id);
            // 
            // dd($user);
            $user->update([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'phonenumber' => $request->phonenumber,
                'email' => $request->email,
                'role' => $request->role,
                'language' => $request->language,
                /// 'password' => Hash::make($request->NewPassword),
            ]);

            if ($request->NewPassword != null) {
                $user->password = Hash::make($request->NewPassword);
                $user->save();
            }
            return response()->json([
                'status' => true,
                'message' => 'User Updated Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function changePasswordByUser(Request $request)
    {
        $user = Auth::user();
        // return $user;
        if (Hash::check($request->CurrentPassword, $user->password)) {

            $user->password = Hash::make($request->NewPassword);
            $user->save();
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Password  Not Changed ',
            ], 300);
        }
        return response()->json([
            'status' => true,
            'message' => 'Password Changed Successfully',
        ], 200);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //        $this->authorize('delete-user');

        $user->delete();
        return response()->json([
            'status' => true,
            'message' => 'User Deleted Successfully',
        ], 200);
    }
}
