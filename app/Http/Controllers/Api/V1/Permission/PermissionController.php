<?php

namespace App\Http\Controllers\Api\V1\Permission;

use App\helpers;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use function PHPUnit\Framework\isEmpty;
use App\Http\Requests\PermissionRequest;

use Spatie\Permission\Models\Permission;
use App\Http\Resources\GeneralCollection;
use App\Http\Resources\PermissionRsource;

class PermissionController extends Controller
{
    use helpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view-assignpermissions');
        //$this->authorize('view-guest');

        $permissions = Permission::get();
        if($permissions->first()){
            return $this->apiResponse(new GeneralCollection($permissions, PermissionRsource::class),200);

        }else{
            return['message' => __('not found')];
        }
    }
    public function getAllPermissionPaginate($id)
    {
        $this->authorize('view-assignpermissions');

        $permissions = Permission::paginate(request()->segment(count(request()->segments())));
        if($permissions->first()){
            return $this->apiResponse(new GeneralCollection($permissions, PermissionRsource::class),200);

        }else{
            return['message' => __('not found')];
        }
    }

    public function getPermissionByID($id)
    {
        $this->authorize('view-assignpermissions');

        $permissions = Permission::where('id',$id)->get();
        if($permissions->first()){
        return $this->apiResponse(['data' => $permissions], 200);
       }else{
        return['message' => 'not found'];
       }
        
       
    }

  

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:permissions|max:254',
        ]);
       // $this->authorize('view-guest');
        $this->authorize('create-permissions');

        $permission = Permission::create(['name' => $request->name]);

        return $this->apiResponse(['data' => $permission], 200);
        
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:permissions|max:254',
        ]);
        $this->authorize('edit-permissions');

        $permission = Permission::where('id',$request->id)->update([
            'name' => $request->name,
        ]);
        $permission = Permission::where('id', $request->id)->get();

        return $this->apiResponse(['data' => $permission], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $this->authorize('delete-permissions');

        $permission = Permission::where('id', $id)->delete();
        if($permission){
            return $this->apiResponse(['message' => 'The Permission Has Been Deleted'], 200);
        }else{
            return $this->apiResponse(['message' => 'not found'], );
        }
        
        

    }

    public function updateUserPermissions(PermissionRequest $request,$id)
    {
        $this->authorize('edit-assignpermissions');

    $user=User::where('id', $id)->first();
    $user->syncPermissions($request->all());

    $userPermissions = Permission::whereHas('users', function ($q) use ($user) {
        $q->where('model_id', $user->id);
    })->get();
    if($userPermissions->first()) {
        return $this->apiResponse(['data' => $userPermissions], 200);

    } else {
        return $this->apiResponse(['message' => 'null'], 200);
    }
    
    }    
    public function getPermissionBySubject()
    {
       // $this->authorize('view-assignpermissions');

        $return =[];
        $eachCategory = [];
        $actions = [];
        $categoryNameArr = [];
        $allPermissions = Permission::get();
        if($allPermissions->first())
        {
            foreach($allPermissions as $onePermission)
            {
                $permissionNameExploaded =  explode('-',$onePermission->name);
                if(!in_array($permissionNameExploaded[1],$categoryNameArr))
                {
                    $eachCategory['subject'] = $permissionNameExploaded[1];
                    foreach($allPermissions as $onePermissionForActions)
                    {
                        $permissionNameForActionExploaded =  explode('-',$onePermissionForActions->name);
                        if($permissionNameForActionExploaded[1] == $permissionNameExploaded[1])
                        {
                            array_push($actions, $permissionNameForActionExploaded[0]);
                        }
                    }
                    $eachCategory['action'] = $actions;
                    array_splice($actions,0);
                    array_push($return, $eachCategory);
                    array_push($categoryNameArr,$permissionNameExploaded[1]);
                }
            }        
            return $this->apiResponse(['data' => $return], 200);
        }else{
            return $this->apiResponse(['message' => 'null'], 200);
        }
       
    }

    public function getUserPermissionByID($id)
    {
      //  $this->authorize('view-assignpermissions');

            $user = user::where('id',$id)->first();
       // $permissions = User::with('permissions')->get();
       if($user)
       {
        $permissions = $user->getDirectPermissions();
        return $this->apiResponse(['data' => $permissions], 200);
       }else{
        return $this->apiResponse(['message' => 'null'], 200);
       }
       
    }

    public function assignPermissionUser(Request $request)
    {

        $this->authorize('create-assignpermissions');

        $user = User::where('id',$request->userID)->first();
        $permission = Permission::where('id',$request->permissionID)->first();

        $user->givePermissionTo($permission->name);
        return 'done';

    }





    
}