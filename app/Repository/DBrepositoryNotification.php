<?php
namespace App\Repository;

use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use LucasDotVin\Soulbscription\Enums\PeriodicityType;
use App\Repositoryinterface\RepositoryNotificationinterface;

class DBrepositoryNotification implements RepositoryNotificationinterface{
    private $notificationModel; 

    public function __construct(Notification $notificationModel)
    {
        $this->notificationModel = $notificationModel;
    }
   
    public function index()
    {
        $notification = $this->notificationModel::latest()->get();
        return $notification;
    }
    public function getLast5()
    {
        $notification = $this->notificationModel::latest()->take(5)->get();
        return $notification;
    }

    public function store($request)
    {
       $createNotification = $this->notificationModel::create([
            'start_date'         => $request->start_date,
            'message'            => $request->message,
            'message_loc'        => $request->message_loc,
            'redirect_to'        => $request->redirect_to,
            'is_main'            => $request->is_main,
            'active'             => $request->active,
            'added_by'           => $request->added_by,
          
        ]);
        $geAllNotification = $this->notificationModel::where('id',$createNotification->id)->get();
        return  $geAllNotification;
    }

    public function show($id)
    {      
        $notification = $this->notificationModel::where('id', $id)->first();
        return $notification;
    }

    public function update($request, $id)
    {
        $notification = $this->notificationModel::where('id', $id)->first();
        if($notification){
            $this->notificationModel::where('id',$id)->Update([
                'start_date'  => (!empty($request['start_date'])) ? $request['start_date'] :  $notification->start_date,
                'message' => (!empty($request->message)) ? $request->message :  $notification->message,
                'message_loc'      => (!empty($request->message_loc)) ? $request->message_loc :  $notification->message_loc,        
                'redirect_to'      => (!empty($request->redirect_to)) ? $request->redirect_to :  $notification->redirect_to,        
                'is_main'      => (!empty($request->is_main)) ? $request->is_main :  $notification->is_main,        
                'active'      => (!empty($request->active)) ? $request->active :  $notification->active,        
                'added_by'      => (!empty($request->added_by)) ? $request->added_by :  $notification->added_by,        
            ]);
            $notification = $this->notificationModel::where('id',$id)->get();
            return  $notification;
        }else{
            return null;
        }       
        
    }


    public function destroy($id)
    {
        $notification=$this->notificationModel::find($id);
        if($notification){
          $notification->delete();
            return 'Deleted';
        }else{
            return null;
        }
    }


}

