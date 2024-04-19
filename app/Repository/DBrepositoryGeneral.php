<?php

namespace App\Repository;

use App\Models\OordService;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Repositoryinterface\Generalinterface;
use LucasDotVin\Soulbscription\Models\Feature;
use LucasDotVin\Soulbscription\Enums\PeriodicityType;
use App\Http\Controllers\Api\V1\Subscription\PlanController;
use App\Models\Guests;
use App\Models\Room;

class DBrepositoryGeneral implements Generalinterface
{
    private $transactionModel;
    private $roomModel;
    private $guestModel;
    private $ooservicesModel;
    private $roomWithStatusArr = [];

    public function __construct(Transaction $transactionModel, Room $roomModel, Guests $guestModel, OordService $ooservicesModel)
    {
        $this->transactionModel = $transactionModel;
        $this->roomModel = $roomModel;
        $this->guestModel = $guestModel;
        $this->ooservicesModel = $ooservicesModel;
    }

    public function total_roomCharge($request)
    {
        // transaction::where(guest_id ,request_guest_id)->where(''window_id,$request_widow_id)->where('ledger_cat',1)->sum('charge_amount)
        $totalRoomCharge = $this->transactionModel::where('guest_id', $request['guest_id'])
            ->where('window_id', $request['window_id'])
            ->whereHas('ledger', function ($q) {
                $q->where('ledger_cat_id', 1);
            })->sum('charge_amount');
        return $totalRoomCharge;
    }
    public function total_fb_charge($request)
    {
        $totalFbCharge = $this->transactionModel::where('guest_id', $request['guest_id'])
            ->where('is_void', 0)
            ->where('window_id', $request['window_id'])
            ->whereHas('ledger', function ($q) {
                $q->where('ledger_cat_id', 2);
            })->sum('charge_amount');

        return $totalFbCharge;
    }
    public function total_other_charge($request)
    {
        $totalOtherCharge = $this->transactionModel::where('guest_id', $request['guest_id'])
            ->where('is_void', 0)
            ->where('window_id', $request['window_id'])
            ->whereHas('ledger', function ($q) {
                $q->whereNotIn('ledger_cat_id', [1, 2]);
            })->sum('charge_amount');

        return $totalOtherCharge;
    }
    public function total_payment($request)
    {
        $totalPayment = $this->transactionModel::where('guest_id', $request['guest_id'])
            ->where('is_void', 0)
            ->where('window_id', $request['window_id'])
            ->sum('payment_amount');

        return $totalPayment;
    }

    public function guestBalance($request)
    {
        //        dd($request);
        // balance=from sum_of charge_amount - sum of payment amount from transaction
        if (empty($request['window_id'])) {
            $balance = $this->transactionModel::where('is_void', 0)
                ->whereHas('guest', function ($q) use ($request) {
                    $q->where('guest_id', $request['guest_id']);
                })
                ->selectRaw('IFNULL(SUM(charge_amount), 0) - IFNULL(SUM(payment_amount), 0) as balance')
                ->first();

            if ($balance == null) {
                return 0;
            }
            return ($balance['balance']);
        } elseif ($request['window_id']) {
            $balance = $this->transactionModel::where('is_void', 0)
                ->whereHas('guest', function ($q) use ($request) {
                    $q->where('guest_id', $request['guest_id']);
                })
                ->whereHas('window', function ($q) use ($request) {
                    $q->where('id', $request['window_id']);
                })
                ->selectRaw('IFNULL(SUM(charge_amount), 0) - IFNULL(SUM(payment_amount), 0) as balance')
                ->first();
            if ($balance == null) {
                return 0;
            }
            return ($balance['balance']);
        }
    }

    public function taxes($request)
    {
        $transactioArr = [];
        $startDate = Carbon::parse($request['startDate']);
        $endDate = Carbon::parse($request['endDate']);
        $data = $this->transactionModel::whereDate('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)
            ->with('taxes')
            ->get();
        foreach ($data as $tranaction) {
            $sumTaxe = $tranaction->taxes()->first()->pivot->where('transaction_id', $tranaction->id)->groupBy('transaction_id')->sum('amount');

            array_push($transactioArr, ['transactioID' => $tranaction->id, "taxes" => $sumTaxe]);
        }
        return $transactioArr;
    }

    public function total($request)
    {
        $transactioArr = [];
        $startDate = Carbon::parse($request['startDate']);
        $endDate = Carbon::parse($request['endDate']);
        $data = $this->transactionModel::whereDate('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)
            ->with('taxes')
            ->get();
        foreach ($data as $tranaction) {
            $total = $tranaction->taxes()->get(['taxes.id'])
                ->map(function ($total) use ($tranaction) {
                    $amount = $total->sumAmount = $total->pivot->where('transaction_id', $tranaction->id)->sum('amount');

                    $total->total = $total->pivot->pivotParent->where('id', $tranaction->id)->get()->map(function ($total) use ($amount) {
                        $total->total = $total->sum('charge_without_taxes') + $amount;
                        return $total;
                    });
                    return $total;
                })[0]->total;
            array_push($transactioArr, $total[0]);
        }
        return $transactioArr;
    }
    /**
     * functions to get status of room in one day
     * if checkRoomStatusInGuestForOneDay and checkRoomStatusInOOservicesForOneDay = null the room status =VA
     */

    public function getAllRoomsOrByIDOrRoomTypeID($roomID = null, $roomTypeID = null)
    {
        if (is_null($roomID) && is_null($roomTypeID)) {
            $roomsHasPax = $this->roomModel::where('rm_max_pax', '>', 0)->pluck('id');
        } elseif (!is_null($roomID) && is_null($roomTypeID)) {
            $roomsHasPax = $this->roomModel::where('id', $roomID)->pluck('id');
        } elseif (is_null($roomID) && !is_null($roomTypeID)) {
            $roomsHasPax = $this->roomModel::where('room_type_id', $roomTypeID)->pluck('id');
        } else {
            $roomsHasPax = $this->roomModel::where('id', $roomID)->pluck('id');
        }
        return $roomsHasPax;
    }

    public function checkRoomStatusInGuestForOneDay($date, $roomID)
    {
        $roomWithStatusArr = [];
        $roomBL = $this->guestModel->where('room_id', $roomID)
            //  ->whereBetween($date, ['in_date','out_date'])
            ->where('in_date', '<=', $date)
            ->where('out_date', '>=', $date)


            ->where('is_reservation', true)->where('is_cancel', false)
            ->get();
        if ($roomBL) {
            foreach ($roomBL as $bl) {
                $roomWithStatusArr["$bl->id"] = "BL";
            }
        } else {
            $roomOC = $this->guestModel->where('room_id', $roomID)
                ->where('in_date', '<=', $date)
                ->where('out_date', '>=', $date)
                ->where('is_checked_in', true)->where('checked_out', false)
                ->count();
            if ($roomOC > 0) {
                return 'OC';
            } else {
                return null;
            }
        }
    }

    public function checkRoomStatusInOOservicesForOneDay($date, $roomID)
    {

        $roomOOorOS = $this->ooservicesModel::where('room_id', $roomID)->where('is_return', false)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->get();
        if (!empty($roomOOorOS->first()->oord_type) == 'OO') {
            return 'OO';
        } elseif (!empty($roomOOorOS->first()->oord_type) == 'OS') {
            return 'OS';
        } else {

            return null;
        }
    }

    public function getStatusByRoom($date, $roomID)
    {
        $roomWithStatusArr = [];
        $result = [];
        // return $roomID;
        $allRoomIDs = $roomID;
        // dd($roomID);
        foreach ($roomID as $oneRoom) {

            $roomWithStatusArr["$oneRoom"] = "VA";
        }

        $roomBL = $this->guestModel
            ->whereIn('room_id', $roomID)
            ->where('in_date', '<=', $date)
            ->where('out_date', '>=', $date)
            ->where('is_reservation', true)->where('is_cancel', false)
            ->get();
        // return $roomBL;
        if ($roomBL) {
            foreach ($roomBL as $bl) {
                $roomWithStatusArr["$bl->room_id"] = "BL";
                // $index = array_search("$bl->room_id",$allRoomIDs->toArray());
                // unset($allRoomIDs["$index"]);

            }
            //dd($roomWithStatusArr);
            $roomOC = $this->guestModel
                ->whereIn('room_id', $roomID)
                ->where('in_date', '<=', $date)
                ->where('out_date', '>=', $date)
                ->where('is_checked_in', true)->where('checked_out', false)
                ->get();
            // dd($roomOC);
            if ($roomOC) {
                foreach ($roomOC as $oc) {
                    $roomWithStatusArr["$oc->room_id"] = "OC";
                    // $index = array_search($oc->room_id,$allRoomIDs->toArray());
                    // unset($allRoomIDs["$index"]);
                }

                $roomOOorOS = $this->ooservicesModel
                    ->whereIn('room_id', $roomID)
                    ->where('is_return', false)
                    ->whereDate('start_date', '<=', $date)
                    ->whereDate('end_date', '>=', $date)
                    ->select('oord_type', 'room_id')->get();

                if ($roomOOorOS->first()) {
                    foreach ($roomOOorOS as $oo) {

                        if ($oo->oord_type == 'OO') {
                            $roomWithStatusArr["$oo->room_id"] = "OO";
                            // $index = array_search($$oo->room_id,$allRoomIDs->toArray());
                            // unset($allRoomIDs["$index"]);
                        } elseif ($oo->oord_type == 'OS') {
                            $roomWithStatusArr["$oo->room_id"] = "OS";
                            // $index = array_search($$oo->room_id,$allRoomIDs->toArray());
                            // unset($allRoomIDs["$index"]);
                        } else {

                            $roomWithStatusArr["$oo->room_id"] = "VA";
                        }
                    }
                }
            }
        }
        foreach ($roomWithStatusArr as $value) {
            array_push($result, $value);
        }
        return  $result;
    }
}
