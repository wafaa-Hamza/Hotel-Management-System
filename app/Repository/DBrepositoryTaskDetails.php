<?php

namespace App\Repository;

use App\helpers;
use Carbon\Carbon;
use App\Models\Room;
use App\Models\Task;
use App\Models\Guests;
use App\Models\TaskDetail;
use App\Models\OordService;
use Illuminate\Http\Request;
use App\Repositoryinterface\RepositoryTaskDetailsinterface;
use Illuminate\Support\Facades\DB;

class DBrepositoryTaskDetails implements RepositoryTaskDetailsinterface
{
    use helpers;
    private $taskDetailModel;
    private $taskModel;
    private $guestModel;
    private $oordServiceModel;
    private $roomModel;

    public function __construct(TaskDetail $taskDetailModel, Task $taskModel, Guests $guestModel, OordService $oordServiceModel, Room $roomModel)
    {
        $this->taskDetailModel = $taskDetailModel;
        $this->taskModel = $taskModel;
        $this->guestModel = $guestModel;
        $this->oordServiceModel = $oordServiceModel;
        $this->roomModel = $roomModel;
    }

    // public function featurePagination()
    // {
    //     $features = $this->featureModel::paginate(request()->segment(count(request()->segments())));
    //     return $features;
    // }
    // public function index()
    // {
    //     $features = $this->featureModel::get();
    //     return $features;
    // }

    // public function geSoftDeletedData()
    // {
    //    $featureTrashed = $this->featureModel::onlyTrashed()->get();

    //    if($featureTrashed->first()){

    //         return $featureTrashed;
    //    }else{

    //         return null;
    //    }

    // }

    // public function restorDataTrashed($id)
    // {
    //     $featureTrashed = $this->featureModel::where('id', $id)->onlyTrashed()->get();
    //     if ($featureTrashed->first()) {
    //         $FeatureRestored = $this->featureModel::withTrashed()->where('id', $id)->restore();
    //         return $featureTrashed;
    //     } else {
    //         return null;
    //     }
    // }
    public function store($request)
    {
        $dataForStore = $this->dataForStore($request);
        $taskDtails = $this->taskDetailModel::create([
            'task_id' => ($request->task_id) ? $request->task_id : $request->id,
            'value' => $dataForStore['value'],
            'expected_finish' => $dataForStore['expected_finish'],
            'actual_finish' => null,
            'late_time' => null,
        ]);
        return $taskDtails;
    }

    public function dataForStore($request)
    {
        $hotelDate  = $this->settings()->first()->hotel_date;
        $taskData = $this->taskModel->where('id', !(empty($request->task_id)) ? $request->task_id : null)
            ->orWhere('name', !(empty($request->name)) ? $request->name : null)
            ->first();
        $expected_finish = $hotelDate . ' ' . $taskData->finish_time;
        if ($taskData->name == 'due_out') {
            $value = $this->dueOut($hotelDate);
        } elseif ($taskData->name == 'payment_required') {
            $value = $this->paymentRequired();
        } elseif ($taskData->name == 'block_rooms') {
            $value = $this->reservitionNotBlock($hotelDate);
        } elseif ($taskData->name == 'confirm_reservation') {
            $value = $this->reservitionNotConferm($hotelDate);
        } elseif ($taskData->name == 'oo&os_Return') {
            $value = $this->OOandOSREturn($hotelDate);
        } elseif ($taskData->name == 'clean_rooms') {
            $value = $this->cleanRooms();
        }

        $data =  [
            'value' => $value,
            'expected_finish' => $expected_finish
        ];
        return $data;
    }
    public function dueOut($hotelData)
    {
        $dueOutCount = $this->guestModel->whereDate('out_date', $hotelData)
            ->where('is_reservation', 0)->where('checked_out', 0)->count();
        if ($dueOutCount) {
            return  $dueOutCount;
        } else {
            return  '0';
        }
    }

    public function paymentRequired()
    {

        // $guestBalance= $this->guestModel->with('transactions',function ($q) {

        // })


        $guestBalancegraterzero = $this->guestModel->whereHas('transactions', function ($q) {
            $q->select(DB::raw('guest_id'), DB::raw('IFNULL(SUM(charge_amount), 0) - IFNULL(SUM(payment_amount), 0)  as balance'))
                ->groupBy('guest_id')
                ->having(DB::raw('balance'), '>=', 0);
        })->count();
        // $guestBalancegraterzero = $this->guestModel->whereHas('transactions', function ($q) {
        //     $q->select("*", DB::raw('SUM(charge_amount) - SUM(payment_amount) as total'))
        //         ->groupBy("guest_id")
        //         ->having('total', '>=', 0);
        // })->toSql();
        // dd($guestBalancegraterzero);
        if ($guestBalancegraterzero == null) {
            return '0';
        }
        return ($guestBalancegraterzero);
    }

    public function reservitionNotBlock($hotelData)
    {

        $countOfReservitionNotBlock = $this->guestModel->where('is_reservation', true)->where('is_cancel', false)
            ->where('is_checked_in', false)
            ->where(function ($q) {
                $q->where('res_status', 'NCF')->orWhere('res_status', 'CNF');
            })
            ->where('room_id', null)->where('in_date', $hotelData)->count();
        return  $countOfReservitionNotBlock;
    }
    public function reservitionNotConferm($hotelData)
    {

        $countOfReservitionNotBlock = $this->guestModel->where('is_reservation', true)->where('is_cancel', false)->where('is_checked_in', false)
            ->where(function ($q) {
                $q->where('res_status', 'NCF');
            })
            ->where('in_date', Carbon::parse($hotelData)->addDay())->count();
        return  $countOfReservitionNotBlock;
    }

    public function OOandOSREturn($hotelData)
    {
        $OOandOSREturnCount = $this->oordServiceModel->where('end_date', $hotelData)->where('is_return', false)->count();
        return $OOandOSREturnCount;
    }

    public function cleanRooms()
    {
        $cleanRoomsCount = $this->roomModel->where('hk_stauts', 'DI')->count();
        return $cleanRoomsCount;
    }
    // public function show($id)
    // {

    //     $plan = $this->featureModel::where('id',$id)->first();
    //     return $plan;
    // }

    public function update($request, $id)
    {
        if (!$request['notdataForStore'] || $request['notdataForStore'] == 1) {
            $dataForStore = $this->dataForStore($request);
        }
        // dd();
        $taskDetails = $this->taskDetailModel->where('task_id', $id)->where('actual_finish', null)->first();
        if ($taskDetails) {
            $updateTaskDtails = $this->taskDetailModel::where('id', $taskDetails->id)->update([
                'task_id' => (!empty($request->task_id)) ? $request->task_id : $taskDetails->task_id,
                'value' => (!empty($request->task_id)) ? $dataForStore['value'] : $taskDetails->value,
                'expected_finish' => (!empty($request->task_id)) ? $dataForStore['expected_finish'] : $taskDetails->expected_finish,
                'actual_finish' => (!empty($request->actual_finish)) ? $request['actual_finish'] : $taskDetails->actual_finish,
                'late_time' => (!empty($request->late_time)) ? $request['late_time'] : $taskDetails->late_time,
            ]);

            $taskDetails = $this->taskDetailModel->where('id', $id)->first();
            return  $taskDetails;
        } else {
            return null;
        }
    }

    public function lateTime($expected_finish, $actual_finish)
    {
        $calckLateTime =  Carbon::parse($expected_finish)->diffInMinutes(Carbon::parse($actual_finish), false);
        return $calckLateTime;
    }

    public function paymentRequiredTaskRefresh()
    {
        $countOfPaymentRequired = $this->paymentRequired();
        if ($countOfPaymentRequired == 0) {
            $expectedFinish = $this->taskDetailModel
                ->whereHas('tasks', function ($q) {
                    return $q->where('name', 'payment_required');
                })
                ->where('actual_finish', null)->whereDate('expected_finish', $this->settings()->first()->hotel_date)
                ->select('id', 'expected_finish')->latest()->first();
            if ($expectedFinish) {
                $request = new Request;
                $request->merge([
                    'name' => 'payment_required ', 'actual_finish' => now()->format('Y-m-d H:i:s'),
                    'late_time' => $this->lateTime($expectedFinish->expected_finish, now()), 'notdataForStore' => '0'
                ]);
                $updatePaymentRequired = $this->update($request, $expectedFinish->id);
            }
        }
    }
    public function reservisionNotBlockTaskRefresh()
    {
        $hotelDate = $this->settings()->first()->hotel_date;
        $countOfReservitionNotBlock = $this->reservitionNotBlock($hotelDate);
        if ($countOfReservitionNotBlock == 0) {
            $expectedFinish = $this->taskDetailModel
                ->whereHas('tasks', function ($q) {
                    return $q->where('name', 'block_rooms');
                })
                ->where('actual_finish', null)->whereDate('expected_finish', $hotelDate)
                ->select('id', 'expected_finish')->latest()->first();
            if ($expectedFinish) {
                $request = new Request;
                $request->merge([
                    'name' => 'block_rooms', 'actual_finish' => now()->format('Y-m-d H:i:s'),
                    'late_time' => $this->lateTime($expectedFinish->expected_finish, now()), 'notdataForStore' => '0'
                ]);
                $updatePaymentRequired = $this->update($request, $expectedFinish->id);
            }
        }
    }
    public function reservisionNotConfermTaskRefresh()
    {
        $hotelDate = $this->settings()->first()->hotel_date;
        $countOfReservitionNotconferm = $this->reservitionNotConferm($hotelDate);
        if ($countOfReservitionNotconferm == 0) {
            $expectedFinish = $this->taskDetailModel
                ->whereHas('tasks', function ($q) {
                    return $q->where('name', 'confirm_reservation');
                })
                ->where('actual_finish', null)->whereDate('expected_finish', $hotelDate)
                ->select('id', 'expected_finish')->latest()->first();
            if ($expectedFinish) {
                $request = new Request;
                $request->merge([
                    'name' => 'confirm_reservation ', 'actual_finish' => now()->format('Y-m-d H:i:s'),
                    'late_time' => $this->lateTime($expectedFinish->expected_finish, now()), 'notdataForStore' => '0'
                ]);
                $update = $this->update($request, $expectedFinish->id);
            }
        }
    }
    public function OOandOSReturnRefresh()
    {
        $hotelDate = $this->settings()->first()->hotel_date;
        $OOandOSREturn = $this->OOandOSREturn($hotelDate);
        if ($OOandOSREturn == 0) {
            $expectedFinish = $this->taskDetailModel
                ->whereHas('tasks', function ($q) {
                    return $q->where('name', 'oo&os_Return');
                })
                ->where('actual_finish', null)->whereDate('expected_finish', $hotelDate)
                ->select('id', 'expected_finish')->latest()->first();
            if ($expectedFinish) {
                $request = new Request;
                $request->merge([
                    'name' => 'oo&os_Return', 'actual_finish' => now()->format('Y-m-d H:i:s'),
                    'late_time' => $this->lateTime($expectedFinish->expected_finish, now()), 'notdataForStore' => '0'
                ]);
                $update = $this->update($request, $expectedFinish->id);
            }
        }
    }

    public function cleanRoomsRefresh()
    {
        $hotelDate = $this->settings()->first()->hotel_date;
        $cleanRoomsCount = $this->cleanRooms();
        if ($cleanRoomsCount == 0) {
            $expectedFinish = $this->taskDetailModel
                ->whereHas('tasks', function ($q) {
                    return $q->where('name', 'clean_rooms');
                })
                ->where('actual_finish', null)->whereDate('expected_finish', $hotelDate)
                ->select('id', 'expected_finish')->latest()->first();
            if ($expectedFinish) {
                $request = new Request;
                $request->merge([
                    'name' => 'clean_rooms', 'actual_finish' => now()->format('Y-m-d H:i:s'),
                    'late_time' => $this->lateTime($expectedFinish->expected_finish, now()), 'notdataForStore' => '0'
                ]);
                $update = $this->update($request, $expectedFinish->id);
            }
        }
    }

    public function publicRefreshTask($functionName, $isNeedHotelDat = null, $taskName)
    {
        $hotelDate = $this->settings()->first()->hotel_date;
        $Count = ($isNeedHotelDat) ? $this->$functionName($hotelDate) : $this->$functionName();
        //dd($Count);
        if ($Count == 0) {
            $expectedFinish = $this->taskDetailModel
                ->whereHas('tasks', function ($q) use ($taskName) {
                    return $q->where('name', $taskName);
                })

                ->where('actual_finish', null)->whereDate('expected_finish', $hotelDate)
                ->select('id', 'expected_finish')->latest()->first();
            if ($expectedFinish) {
                $request = new Request;
                $request->merge([
                    'name' => "$taskName", 'actual_finish' => now()->format('Y-m-d H:i:s'),

                    'late_time' => $this->lateTime($expectedFinish->expected_finish, now()), 'notdataForStore' => '0'
                ]);
                $update = $this->update($request, $expectedFinish->id);
            }
        }
    }



    // public function destroy($id)
    // {
    //     $feature=$this->featureModel::find($id);
    //     if($feature){
    //         $feature->delete();
    //         return $feature;
    //     }else{
    //         return null;
    //     }
    // }

    // public function DBDelete($id)
    // {
    //     $feature=$this->featureModel::withTrashed()->find($id);
    //     if($feature){
    //       DB::table('features')->where('id', $id)->delete();
    //         return $feature;
    //     }else{
    //         return null;
    //     }

    // }

}
