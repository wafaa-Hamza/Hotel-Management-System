<?php

namespace App\Repository\Integration;

use App\helpers;
use App\Models\Guests;
use App\Models\Shomoos;
use Illuminate\Support\Arr;
use Spatie\Multitenancy\Models\Tenant;
use App\Repositoryinterface\Integration\ShomoosInterface;

class DBrepositoryShomoos implements ShomoosInterface
{
    use helpers;
    private $shomoosModel;
   
    public function  __construct(Shomoos $shomoosModel)
    {
        $this->shomoosModel = $shomoosModel;
      
    }

    public function index()
    {
        $shomoos = $this->shomoosModel::get();
        return $shomoos ;
    }
    public function show($id)
    {
        $shomoos = $this->shomoosModel::where('id', $id)->first();
        return $shomoos;
    }
    public function getShomoosByGuest_id($id)
    {
        $shomoos = $this->shomoosModel::where('guest_id', $id)->first();
        return $shomoos;
    }
    public function getShomoosByGuestIdAndApi($request)
    {
        $shomoos = $this->shomoosModel::where('guest_id', $request->id)->where('api',$request->api)->get();
        return $shomoos;
    }
    public function getShomoosFailedByGuestIdAndApi($request)
    {
        $shomoos = $this->shomoosModel::where('guest_id', $request->id)->where('api',$request->api)
        ->where('response_code','!=',200)->get();
        return $shomoos;
    }
    public function getfailedShomoosForGuest($request)
    {
        $shomoos = $this->shomoosModel::where('guest_id', $request->id)->where('response_code','!=',200)->get();
        return $shomoos;
    }
    public function getAllFailedShomoos()
    {
        $shomoos = $this->shomoosModel::where('response_code','!=',200)->orWhere('response_code','!=',201)
        ->WhereHas('guest',function($q){
            $q->where('checked_out','!=',1)->where('is_sent_shomoos',false);
        })
        ->get();
        return $shomoos;
    }

   // public function store($request)
    // {
    //     $shomoos = $this->shomoosModel::create([
    //         'response_code' =>(!empty($request->response_code)) ? $request->response_code : 0,
    //         'response_message' =>(!empty($request->response_message)) ? $request->response_message : 0,
    //         'request' => $request->shomoosRequest,
    //         'api' =>$request->api ,
    //         'guest_id' =>$request->guest_id,
    //     ]);
    //     return $shomoos;
    // }
    public function store($request)
    {
        $tenant = Tenant::current();
        if($tenant->is_shomoos == 0)
        {
            return 0;
        }
        $shomoos = $this->shomoosModel::create([
            'response_code' =>(!empty($request->response_code)) ? $request['response_code'] : 0,
            'response_message' =>(!empty($request->response_message)) ? $request['response_message'] : 0,
            //'request' => $request['shomoosRequest'],
            'request' =>json_encode(Arr::except ($request,'api')),
            'api' =>$request['api'] ,
            'guest_id' =>$request['pms']['pms_guest_id'],
        ]);
        return $shomoos;
    }

    // public function storeAfterHandel($request)
    // {
    //     dd($request);
    //     $shomoos = $this->shomoosModel::create([
    //         'response_code' =>(!empty($request->response_code)) ? $request['response_code'] : 0,
    //         'response_message' =>(!empty($request->response_message)) ? $request['response_message'] : 0,
    //         'request' => $request['shomoosRequest'],
    //         'api' =>$request['api'] ,
    //         'guest_id' =>$request['pms']['pms_guest_id'],
    //     ]);
    //     return $shomoos;
    // }
    public function update($request, $id)
    {
        $shomoos = $this->show($id);

        if ($shomoos) {
            $this->shomoosModel::where('id', $id)->update([
                'response_code' =>(!empty($request->response_code)) ? $request->response_code : $shomoos->response_code,
                'response_message' =>(!empty($request->response_message)) ? $request->response_message : $shomoos->response_message,
                'request' =>(!empty($request->request)) ? $request->request : $shomoos->request,
                'api' =>(!empty($request->api)) ? $request->api : $shomoos->api ,
                'guest_id' =>(!empty($request->guest_id)) ? $request->guest_id : $shomoos->guest_id,
            ]);

            $shomoos = $this->show($id);

            return $shomoos;
        } else {
            return null;
        }
    }

    public function destroy($id)
    {

        $shomoos = $this->show($id);

        if ($shomoos) {
            $shomoos = $shomoos->delete();
            return $shomoos;
        } else {
            return null;
        }
    }

}
