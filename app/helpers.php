<?php
namespace App;

use App\Models\Setting;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Multitenancy\Models\Tenant;

trait helpers{
    function apiResponse($data, $status = 200)
    {
        return response()->json($data, $status);
    }
/**
 * $request = $request that hase file
 * $path like 'storage/expensesFile'
 * $prefixName = any name pefore image NAme
 * $lastFile = the last file from DB if you want update it
 * return fileName
 */
    private function uploadFile($request,$path,$prefixName,$lastFile = null)
    {
        $arrDBNameSaved = [];
        if(is_array($request->file))
        {
                $files = $request->file('file');
                foreach($files as $file)
                {
                    $fileNanme = $prefixName . '_' . time() . '_' . $file->getClientOriginalName();
                    $file->move($path, $fileNanme);
                    $DBNameSaved = $path.'/'.$fileNanme;
                    array_push($arrDBNameSaved,$DBNameSaved);
                }
                return $arrDBNameSaved;
        }else{
            $file = $request->file('file');
            $fileNanme = $prefixName . '_' . time() . '_' . $file->getClientOriginalName();
            $file->move($path, $fileNanme);
            $DBNameSaved = $path.'/'.$fileNanme;
        }
        


        if($lastFile)
        {
            Storage::delete($lastFile);
        }
        return $DBNameSaved;
 
    }
    private function deleteFile($request)
    {
            Storage::delete($request);
             return true;
 
   }
   
   private function settings()
   {
    $tenant_id = Tenant::current();
    if($tenant_id)
    {
         $settings = cache('settings_'.$tenant_id->id);
    }else{
        $settings = Setting::get();
    }
    return $settings;
   }
}
