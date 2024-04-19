<?php

namespace App\Repository\profiles\guestProfile;

use App\helpers;
use Carbon\Carbon;
use App\Models\Guests;
use App\Models\RoomType;
use Illuminate\Http\Request;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryReservationChartInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryReservisionChartSummaryInterface;

class DBrepositoryReservatsionChart implements RepositoryReservationChartInterface
{

    use helpers;
    private $guestModel;
    private $repoSummaryInterface;
    private $roomTypeModel;


    public function __construct(Guests $guestModel,  RoomType $roomTypeModel,RepositoryReservisionChartSummaryInterface $repoSummaryInterface)
     {
        $this->guestModel = $guestModel;
        $this->repoSummaryInterface = $repoSummaryInterface;
         $this->roomTypeModel = $roomTypeModel;
    }

    public function roomType()
    {
        $allRoomTypes = $this->roomTypeModel->where('rm_type_rentable',1)
            ->where('def_pax','>',0)->select('id','rm_type_name','rm_type_name_loc')->get();
        return  $allRoomTypes;
    }
    public function reservationChart($request)
    {
        $return = [];
        $groupData['group'] = [];
        $groupData['individaul'] = [];
        $startDate = Carbon::parse($request->start_date);
        $groupData['roomTypes'] = $this->roomType();

        $endDate = Carbon::parse($request->end_date);
        $dateOnly = 0;
        $firstDateIndividaul = 1;
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) 
        {
            
            if($dateOnly == 0)
            {
              $getGroupData = $this->getGroupData($date,$dateOnly);

              if(!empty($getGroupData))
              {
                  array_push( $groupData['group'],$getGroupData);
                  $groupData['group'] = collect($groupData['group'])->flatten(1)->toArray();
                  $dateOnly = 1;
              }

            }else{
                $getGroupData = $this->getGroupData($date,$dateOnly);
                if(!empty($getGroupData))
                {
                    foreach($getGroupData as $oneDate)
                    {
                        $result = collect($groupData['group'])->map(function ($array) use ($oneDate,$groupData,$date) {
                            $return = isset($array['guest_name']) && $array['guest_name'] === $oneDate['guest_name'];
                                if($return)
                                {
                                    $array[$date->format('Y-m-d')] = $oneDate[$date->format('Y-m-d')];
                                   //array_push($array,$oneDate);
                                }
                            return $array;
                        });
                // dd($result);
                $groupData['group'] = $result ;
                    }

                }
            }
        }

        $getGuestIndividaul = $this->getGuestIndividaul($request);
        // dd($getGuestIndividaul);
          if(!empty($getGuestIndividaul))
          {
                  array_push( $groupData['individaul'],$getGuestIndividaul);
          }
        return $groupData;

    }

    public function getGuestIndividaul($request,$firstDateIndividaul = null)
    {
        $individaulGuest = [];
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $opject = [];
        $guests = $this->guestModel
        //->whereDate('in_date','<=',$startDate)

        ->where('in_date','>=',$startDate)
        ->where('in_date','<=',$endDate)

        ->orWhere('in_date','<=',$startDate)
        ->where('out_date','>=',$startDate)
        ->where('out_date','<=',$endDate)


        ->where('is_cancel',0)
        ->where('is_group',0)
        ->where('room_type_id','!=',null)
        ->whereHas('roomType',function($q){
            $q->where('rm_type_name','!=','PayMaster');
        })
        ->with(['profile' => function($q){ 
            $q->select('id','first_name','last_name');
        }])

        ->with(['roomType' => function($q){
            $q->select('id','rm_type_name')->where('rm_type_name','!=','PayMaster');
        }])

        ->with(['meal' => function($q){
            $q->select('id','name');
        }])

        ->select('id','no_of_pax','room_type_id','profile_id','in_date','out_date')
        ->get();
        foreach($guests as $guest)
        {
            if(!empty($guest->profile))
            {
                $opject['guest_name'] = $guest->profile->first_name .' ' . $guest->profile->last_name;
                $opject['guest_id'] = $guest->id ;
        
                for ($date = $startDate; $date <= $endDate; $date = date('Y-m-d', strtotime($date . ' +1 day'))) 
                {
                  if($date >= $guest->in_date && $date <= $guest->out_date)
                    {
                        $opject[Carbon::parse($date)->format('Y-m-d')] = 1 ;

                    }

                }

            }
            else{
             
                continue;
            }
            
         //dd($guest->roomType->rm_type_name);          
         if(!empty($guest->roomType))
         {
            $opject[$guest->roomType->rm_type_name] = 1;
         }
        
                    $opject["no_of_pax"] = $guest->no_of_pax;
                    array_push($individaulGuest,$opject);
                    $opject = [];
        }
        return $individaulGuest;
    }
    public function getPaymaster($date)
    {
        $guestPayMaster = $this->guestModel
        ->whereDate('in_date','<=',$date)
        ->where('out_date','>=',$date)

        ->whereHas('roomType',function($q){
            $q->where('rm_type_name','payMaster')
           
            ->select('id');
        })
       
        ->with(['profile' => function($q){ 
            $q->select('id','first_name','last_name');
            return $q;
        }])
        ->select('id','group_code','profile_id')
        ->get();
        return $guestPayMaster;
    }

    public function getGroupData($date , $dateOnly = null)
    {
        $guestArr = [];
        $opject =[];
//        dd($dateOnly,$date);

        foreach($this->getPaymaster($date) as $payMaster)
        {

            $guests = $this->guestModel->whereDate('in_date','<=',$date)
            ->where('out_date','>=',$date)->where('is_cancel',0)
            ->where('group_code',$payMaster->group_code)
            ->where('room_type_id','!=',null)

            ->whereHas('roomType',function($q){
                $q->where('rm_type_name','!=','payMaster')
               
                ->select('id');
            })

            ->with(['roomType' => function($q){
                $q->select('id','rm_type_name')->where('rm_type_name','!=','payMaster');
            }])
            
            ->with(['meal' => function($q){
                $q->select('id','name');
            }])
            
            ->select('id','no_of_pax','room_type_id')
            ->get();

            $opject['guest_name'] = $payMaster->profile->first_name .' ' . $payMaster->profile->last_name;
            $opject['guest_id'] = $payMaster->id;
        if($dateOnly == 0)
        {
            foreach($this->repoSummaryInterface->roomType() as $roomType)
            {

                if($payMaster && !empty($payMaster->profile))
                {
                    $opject[$roomType->rm_type_name] = $guests->where('room_type_id',$roomType->id)->count();
                }
            }
        }
            if($payMaster && !empty($payMaster->profile))
            {
                $opject[$date->format('Y-m-d')] = $guests->count();
            }
                if($dateOnly == 0)
                {
                if($payMaster && !empty($payMaster->profile))
                            {
                                    // $guestArr[$payMaster->profile->first_name .' ' . $payMaster->profile->first_name ]["meal"] =
                                    // $guests->meal->name; //discuss;
                    
                                    $opject["no_of_pax"] = $guests->sum('no_of_pax'); 
                            }
                }
               // dd($guestArr);
              // return $opject;
            array_push($guestArr,$opject);
            $opject = [];
        }
        return $guestArr;
    }

 
}
