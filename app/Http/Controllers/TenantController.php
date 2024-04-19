<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Setting;
//use Spatie\Multitenancy\Models\Tenant;
use App\Models\CustomTenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Multitenancy\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;



//use Spatie\FlareClient\Http\Response;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //        $this->authorize('view-tenant');

        $tenanat = Tenant::all();
        return response()->json($tenanat);
    }

    public function checkTenant()
    {
        $tenant = Tenant::checkCurrent();
        if(!$tenant)
        {
            return response()->json(['mesaage' => 'Tenant notFound'],404);
        }else{
            return response()->json(['mesaage' => 'Tenant Found'],200);

        }
    }
    public function tenantPagination()
    {
        //        $this->authorize('view-tenant');

        $tenanat = Tenant::paginate(request()->segment(count(request()->segments())));
        return response()->json($tenanat);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generateDBName($request)
    {
        $dbName = "masa" . '_' . explode(".", $request)[0] . now()->format('ymdHi');
        return $dbName;
    }

    public function createTenantDBSechema($request)
    {


        try {
           DB::beginTransaction();
            $spatiTenant = new Tenant;
            Schema::connection('tenant')->createDatabase($request->database);//to create database structure in sql server
           $migrateAndSeed = $spatiTenant::where('id',$request->id)->first()->execute(function () {
                 Artisan::call('migrate'); 
                 Artisan::call('db:seed'); 
             });

             return true;
       }catch(Exception $e)
       {

          DB::rollBack();
           return $e->getMessage();
       }
    }

    public function store(Request $request)
    {

        // dd(config('auth.defaults.guard'));

        $rules = [
            'clientname' => 'required|max:20',
            'tenantname' => 'required|max:20',
            'domain' => 'required|max:20',
            'database' => 'required',
            'email' => 'email|nullable',
            'phonenumber' => 'numeric|required',
            'address' => 'required|max:255',
        ];

        $messages = [
            'domain.unique' => 'The domain has already been taken.',
            'database.unique' => 'The database has already been taken.',
            'address.required' => 'The address field is required.',
            // Add more custom error messages if needed
        ];

        $validateUser = Validator::make($request->all(), $rules, $messages);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors(),
            ], 401);
        }
        try {
            DB::beginTransaction();
        $databaseName = $this->generateDBName($request->domain);

        $newTenant = CustomTenant::create([
            'clientname'    => $request->clientname,
            'tenantname'    => $request->tenantname,
            'domain'        => $request->domain,
            'database'      => $databaseName,
            'phonenumber'   => $request->phonenumber,
            'address'       => $request->address,
            'taxnumber'     => $request->taxnumber,
            'email'     => $request->email,
            'language_id'     => $request->language_id,
        ]);
        $schemaRequest = new Request;
        $arr = [
            'database' => $databaseName,
            'id' => $newTenant->id,
        ];
        $schemaRequest->merge($arr);
        $createSchemaResponse = $this->createTenantDBSechema($schemaRequest);
        DB::connection()->statement("USE `$databaseName`");
        Setting::first()->update([
            'name' => $request->clientname,
            'name_loc' => $request->clientname,
            'phone' => $request->phonenumber,
            'vat_no' => (!empty($request->taxnumber)) ? $request->taxnumber : 3,
            'address' => $request->address,
            'city' => (!empty($request->city)) ? $request->city : null,
         ]);

         User::where('role','owner')->update([
            'email' => (!empty($request->email)) ? $request->email : null,
            'phonenumber' => $request->phonenumber,
         ]);

        return response()->json([
            'status' => true,
            'message' => 'Welcome To Our Site',
            ($createSchemaResponse != true) ? "error" : "status" => $createSchemaResponse
        ], 200);
    }catch(Exception $e){

        DB::rollBack();
        return $e->getMessage();
    }


 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tenant  $tenant
     * @return \Illuminate\Http\Response
     */
    public function show(Tenant $tenant)
    {
        //        $this->authorize('view-tenant');

        $tenanat = Tenant::all();
        return response()->json($tenanat);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tenant  $tenant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tenant $tenant)
    {
        $validateUser = Validator::make($request->all(), [
            'clientname'     => 'required|max:20',
            'tenantname'     => 'required|max:20',
            'domain'         => 'required|max:20|unique',
            'database'       => 'required' | 'unique',
            'email'          => 'email|required_without:phonenumber',
            'phonenumber'    => 'numeric|required_without:email',
            'address'        => 'required'

        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors(),
            ], 401);
        }
        //        $this->authorize('edit-tenant');

        $tenant->update([
            'clientname'    => $request->clientname,
            'tenantname'    => $request->tenantname,
            'domain'        => $request->domain,
            'database'      => $request->database,
            'phonenumber'   => $request->phonenumber,
            'address'       => $request->address,
            'taxnumber'     => $request->taxnumber,


        ]);
        return response()->json([
            'status' => true,
            'message' => 'Data Updated Successfully',
        ], 401);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tenant  $tenant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tenant $tenant)
    {
        //        $this->authorize('delete-tenant');

        $tenant->delete();
        return response()->json([
            'status' => true,
            'message' => 'Tenant Deleted Successfully',
        ], 200);
    }
}
