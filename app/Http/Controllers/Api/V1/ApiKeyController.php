<?php

namespace App\Http\Controllers\Api\V1;

use App\helpers;
use App\Models\ApiKey;
use Illuminate\Http\Request;
use App\helpers as AppHelpers;
use App\Models\ApiKeyAdminEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class ApiKeyController extends Controller
{
    use helpers;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $keys =ApiKey::orderBy('name')->get();
    if($keys->first()) {
        return $this->apiResponse(['data' => $keys]);
    } else {
        return $this->apiResponse([
            'status' => false,
            'message' => 'Not Found',
            'data'=>[]
                ]);

    }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $keyGeneratedData = Artisan::call('apikey:generatekey'.' '.$request->keyName);
if($keyGeneratedData){
   $dataUpdated = ApiKey::where('name',$request->keyName)->update([
        'domain' => $request->getHost(),
        'expired_date' =>($request->has('expired_date')) ? $request->expired_date : now()->addYear()
       ]);
       
      $apiKeyData = ApiKey::where('name',$request->keyName)->first();
       return $this->apiResponse(['data' =>  $apiKeyData,'status' => true]);
}    else{
    return $this->apiResponse([
        'status' => false,
        'message' => 'not created api key',
        'data'=>[]
            ]);

}   
      
    }
    public function activateApiKey(Request $request)
    {
       $keyActivateData = Artisan::call('apikey:activatekey'.' '.$request->keyName);
if($keyActivateData){
       return $this->apiResponse(['essagem' => 'Api Key Activete']);
}else{
    return $this->apiResponse([
        'status' => false,
        'message' => 'Not Active Api Key',
        'data'=>[]
            ]);

}   
      
    }
    public function deActivateApiKey(Request $request)
    {
       $keyDeActivateData = Artisan::call('apikey:deactivatekey'.' '.$request->keyName);
if($keyDeActivateData){
       return $this->apiResponse(['essagem' => 'Api Key Deactivate']);
}else{
    return $this->apiResponse([
        'status' => false,
        'message' => 'Not Deactivete Api Key',
        'data'=>[]
            ]);

}   
      
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    $keys =ApiKey::orderBy('name')->where('id', $id)->get();
    if($keys->first()) {
        return $this->apiResponse(['data' => $keys]);
    } else {
        return $this->apiResponse([
            'status' => false,
            'message' => 'Not Found',
            'data'=>[]
                ]);

    }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
