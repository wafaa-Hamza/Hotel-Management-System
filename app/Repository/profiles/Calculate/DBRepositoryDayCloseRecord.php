<?php

namespace App\Repository\profiles\Calculate;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\Guests;
use App\Models\Setting;
use App\Models\Statement;
use App\Models\ExtendStay;
use App\Models\Transaction;
use App\Models\DayCloseRecord;
use App\Repositoryinterface\Profiles\Calculate\RepositoryDayCloseRecordInterface;
use Spatie\Multitenancy\Models\Tenant;

class DBRepositoryDayCloseRecord implements RepositoryDayCloseRecordInterface
{
    private $roomModel;
    private $guestModel;
    private $transactionModel;
    private $statementModel;
    private $extendStayModel;
    private $dayCloseRecordModel;
    private $tenantModel;
    public function __construct(DayCloseRecord $dayCloseRecordModel, Room  $roomModel, Guests $guestModel, Transaction $transactionModel,
     Statement $statementModel, ExtendStay $extendStayModel,Tenant $tenantModel)
    {

        $this->roomModel =  $roomModel;
        $this->guestModel =  $guestModel;
        $this->transactionModel =  $transactionModel;
        $this->statementModel =  $statementModel;
        $this->extendStayModel =  $extendStayModel;
        $this->tenantModel =  $tenantModel;
        $this->dayCloseRecordModel =  $dayCloseRecordModel;
    }

    public function store($data)
    {
        $hotelDate = Setting::select('hotel_date')->first()->hotel_date;
        $systemDate = Carbon::now();
        $dayCloseRecordModel = new $this->dayCloseRecordModel;
        $dayCloseRecordModel->hotel_date = $hotelDate;
        $dayCloseRecordModel->sys_date = $systemDate;
        $dayCloseRecordModel->total_room = ($this->getAllRoom() != null) ? $this->getAllRoom() : 0;
        $dayCloseRecordModel->os_rooms = ($this->totalRoomByFoStatus($fo_status = 'OS') != null) ? $this->totalRoomByFoStatus($fo_status = 'OS') : 0;
        $dayCloseRecordModel->oo_rooms = ($this->totalRoomByFoStatus($fo_status = 'OO') != null) ? $this->totalRoomByFoStatus($fo_status = 'OO') : 0;
        $dayCloseRecordModel->oc_rooms = ($this->totalRoomByFoStatus($fo_status = 'OC') != null) ? $this->totalRoomByFoStatus($fo_status = 'OC') : 0;
        $va =($this->totalRoomByFoStatus($fo_status = 'VA') != null) ? $this->totalRoomByFoStatus($fo_status = 'VA') :0;
         $bl = ($this->totalRoomByFoStatus($fo_status = 'BL') != null) ?  $this->totalRoomByFoStatus($fo_status = 'BL') : 0;
        $dayCloseRecordModel->va_rooms = $va + $bl;
        $dayCloseRecordModel->comp_rooms =( $this->getCountOfCompOrHousRooms($way_of_payment = 'COMP') != null) ? $this->getCountOfCompOrHousRooms($way_of_payment = 'COMP') :0;
        $dayCloseRecordModel->house_use_rooms =( $this->getCountOfCompOrHousRooms($way_of_payment = 'HUSU') != null) ?  $this->getCountOfCompOrHousRooms($way_of_payment = 'HUSU') :0;
        $dayCloseRecordModel->guest_ledger = ($this->guestLedger($data) != null) ? $this->guestLedger($data) : 0;
        $dayCloseRecordModel->pre_bill_chrg = 0;
        $dayCloseRecordModel->dcl_balance = ($this->Calc_Of_AR_Balance() != null) ? $this->Calc_Of_AR_Balance() :0;
        $dayCloseRecordModel->cl_transfare = 0;
        $dayCloseRecordModel->cl_payment = ($this->Calc_Of_AR_Deposit_Payment() != null) ? $this->Calc_Of_AR_Deposit_Payment() : 0 ;
        $dayCloseRecordModel->guest_inhouse_pax = ($this->guestInhousPax($data) != null) ? $this->guestInhousPax($data) : 0;
        $dayCloseRecordModel->act_chkin_rooms = ($this->getCheckInRooms($data) != null) ? $this->getCheckInRooms($data) :0;
        $dayCloseRecordModel->act_chkin_pax = ($this->sumOfCheckInPax($data) != null) ? $this->sumOfCheckInPax($data) : 0 ;
        $dayCloseRecordModel->act_chkout_rooms = ($this->getCheckOutRooms($data) != null) ? $this->getCheckOutRooms($data) : 0;
        $dayCloseRecordModel->act_chkout_pax = ($this->sumOfCheckOutPax($data) != null) ? $this->sumOfCheckOutPax($data) : 0 ;
        $dayCloseRecordModel->guest_group = ($this->getCountOfGroup($data) != null) ?$this->getCountOfGroup($data) : 0;
        $dayCloseRecordModel->no_of_vip = ($this->guestNoOfVIP($data) != null) ? $this->guestNoOfVIP($data) :0;
        $dayCloseRecordModel->res_today = ($this->getReservisionToday($data) != null) ? $this->getReservisionToday($data) :0;
        $dayCloseRecordModel->res_cancel = ($this->getReservisionCancel($data) != null) ? $this->getReservisionCancel($data) :0;
        $dayCloseRecordModel->res_noshow_rooms = 0;
        $dayCloseRecordModel->res_noshow_pax = 0;
        $dayCloseRecordModel->early_chkout_rooms = 0;
        $dayCloseRecordModel->early_chkout_pax = 0;
        $dayCloseRecordModel->walkin_pax = 0;
        $dayCloseRecordModel->extended_rooms = ($this->Count_Of_Extended_Rooms($data) != null) ? $this->Count_Of_Extended_Rooms($data) :0;
        $dayCloseRecordModel->extended_pax = ($this->Sum_Of_Extended_Pax($data) != null) ? $this->Sum_Of_Extended_Pax($data) :0;
        $dayCloseRecordModel->walkin_rooms = 0;
        $dayCloseRecordModel->day_use_rooms = ($this->dayUseRoomCount($data) != null) ? $this->dayUseRoomCount($data) : 0;
        $dayCloseRecordModel->day_use_pax =   ($this->dayUseSumOfPax($data) != null) ?$this->dayUseSumOfPax($data) :0;
        $dayCloseRecordModel->expt_in_tmrw_rooms = ($this->expectedtomorrowCount($data) != null) ?$this->expectedtomorrowCount($data) :0;
        $dayCloseRecordModel->expt_in_tmrw_pax = ($this->expectedtomorrowSumPax($data) != null) ?$this->expectedtomorrowSumPax($data) :0;
        $dayCloseRecordModel->expt_out_tmrw_rooms = ($this->expectedOuttomorrowRoomsCount($data) != null) ?$this->expectedOuttomorrowRoomsCount($data) :0;
        $dayCloseRecordModel->expt_out_tmrw_pax = ($this->expectedOuttomorrowSumPax($data) != null) ?$this->expectedOuttomorrowSumPax($data) :0;
        $dayCloseRecordModel->total_rate_room = ($this->totalFbRateromeOthersIndrate($data, $ledgerID = 1) != null) ? $this->totalFbRateromeOthersIndrate($data, $ledgerID = 1) : 0;
        $dayCloseRecordModel->total_fb = ($this->totalFbRateromeOthersIndrate($data, $ledgerID = 2) != null) ? $this->totalFbRateromeOthersIndrate($data, $ledgerID = 2) :0;
        $dayCloseRecordModel->total_taxes = 0;
        $dayCloseRecordModel->total_others = ($this->totalFbRateromeOthersIndrate($data, $ledgerID = 5) != null) ? $this->totalFbRateromeOthersIndrate($data, $ledgerID = 5) :0;
        $dayCloseRecordModel->ind_rate = ($this->totalFbRateromeOthersIndrate($data, $ledgerID = 1) != null) ?$this->totalFbRateromeOthersIndrate($data, $ledgerID = 1) : 0;
        $dayCloseRecordModel->total_paymants = ($this->totalPaymants($data) != null) ? $this->totalPaymants($data) : 0;
        $dayCloseRecordModel->group_rate = 0;
        $data = $dayCloseRecordModel->save();
        return $data;

        // $storeInDayCloseRecord =
    }


    public function totalRoomByFoStatus($foStatus=null)
    {
        if (!$foStatus) {
            $foStatus = ['OS' => 0, 'OO' => 0, 'OC' => 0, 'VA' => 0];
            foreach ($foStatus as $key => $fo) {
                $totalRoomByOFStatus = $this->roomModel::where('fo_status', $key)->count();
                $foStatus[$key] = $totalRoomByOFStatus;
            }

            return  $foStatus;
        } else {
            $totalRoomByOFStatus = $this->roomModel::where('fo_status', $foStatus)->count();
            return  $totalRoomByOFStatus;
        }
    }

    public function getCheckInRooms($date)
    {
        if (!$date->hotelDate) {
            $hotelDate = Setting::select('hotel_date')->first()->hotel_date;
            $chekInRooms = $this->guestModel::where('is_checked_in', true)->where('in_date', $hotelDate)
            ->where('no_of_pax','>',0)
            ->count();
            return $chekInRooms;
        } else {
            $hotelDate = Carbon::parse($date->hotelDate);

            $chekInRooms = $this->guestModel::where('is_checked_in', true)->where('in_date', $hotelDate)
            ->where('no_of_pax','>',0)
            ->count();
            return $chekInRooms;
        }
    }

    public function getCheckOutRooms($date)
    {
        if (!$date->hotelDate) {
            $hotelDate = Setting::select('hotel_date')->first()->hotel_date;

            $chekOutRooms = $this->guestModel::where('checked_out', true)->where('out_date', $hotelDate)
            ->where('no_of_pax','>',0)
            ->count();
            //return $chekOutRooms;
        } else {
            $hotelDate = Carbon::parse($date->hotelDate);

            $chekOutRooms = $this->guestModel::where('checked_out', 1)->where('out_date', $hotelDate)
            ->where('no_of_pax','>',0)
            ->count();
            return $chekOutRooms;
        }
    }

    public function getReservisionCancel($date)
    {
        if (!$date->hotelDate) {
            $hotelDate = Setting::select('hotel_date')->first()->hotel_date;
            $reservationCancelCount = $this->guestModel::where('is_cancel', true)->whereDate('cancel_date', $hotelDate)
            ->where('no_of_pax','>',0)
            ->count();
            return $reservationCancelCount;
        } else {
            $hotelDate = Carbon::parse($date->hotelDate);
            $reservationCancelCount = $this->guestModel::where('is_cancel', true)->whereDate('cancel_date', $hotelDate)
            ->where('no_of_pax','>',0)
            ->count();
            return $reservationCancelCount;
        }
    }
    public function getReservisionToday($date)  // created at done
    {
        if ($date->hotelDate == null) {
            $todayDate = Carbon::today();
            $reservationToday = $this->guestModel::where('is_cancel', false)->whereDate('created_at', $todayDate)
            ->where('no_of_pax','>',0)
            ->count();
            return $reservationToday;
        } else {
            $hotelDate = Carbon::parse($date->hotelDate);
            $reservationToday = $this->guestModel::where('is_cancel', false)->whereDate("created_at", $hotelDate)
            ->where('no_of_pax','>',0)
            ->count();
            return $reservationToday;
        }
    }

    public function guestLedger($date = null)
    {
        if (empty($date->hotelDate)) {
            $hotelDate = Setting::select('hotel_date')->first()->hotel_date;
           // $hotelDate = Carbon::parse($hotelDate);
            $res = Transaction::where('hotel_date', $hotelDate)
                ->where('is_void', 0)->get()->sum(function ($q)use($hotelDate) {
                    $sumOfToday = $q->charge_amount - $q->payment_amount;

                    return  $sumOfToday;
                });
                $guestLedgerForYesterDay = DayCloseRecord::whereDate('hotel_date',Carbon::parse( $hotelDate)->subDay())->select('guest_ledger')->first();
                  $guest_ledger =  ($guestLedgerForYesterDay)? $guestLedgerForYesterDay->guest_ledger : 0 ;
                 
                $result = $guest_ledger + ($res);

        } else {
            $hotelDate = Carbon::parse($date->hotelDate);
            $res = Transaction::where('hotel_date', $hotelDate)
                ->where('is_void', 0)->get()->sum(function ($q)use($hotelDate) {
                    $sumOfToday = $q->charge_amount - $q->payment_amount;

                    return  $sumOfToday;
                });
                
                $guestLedgerForYesterDay = DayCloseRecord::whereDate('hotel_date',Carbon::parse( $hotelDate)->subDay())->select('guest_ledger')->first();
                $guest_ledger =  ($guestLedgerForYesterDay)? $guestLedgerForYesterDay->guest_ledger : 0 ;
              $result = $guest_ledger + ($res);
        }


        return $result;
    }

    public function getAllRoom()
    {
        $allRoomCount = $this->roomModel::count();
        return $allRoomCount;
    }

    public function sumOfCheckInPax($date)
    {
        if (!$date->hotelDate) {
            $todayDate = Carbon::today();
            $sumCheckInPax = $this->guestModel::where('in_date', $todayDate)->where('is_checked_in', true)
            ->where('no_of_pax','>',0)
            ->sum('no_of_pax');
            return $sumCheckInPax;
        } else {
            $hotelDate = Carbon::parse($date->hotelDate);
            $sumCheckInPax = $this->guestModel::where('in_date', $hotelDate)->where('is_checked_in', true)
            ->where('no_of_pax','>',0)
            ->sum('no_of_pax');
            return $sumCheckInPax;
        }
    }
    public function sumOfCheckOutPax($date)
    {
        if ($date->hotelDate) {
            $todayDate = Carbon::today();
            $sumCheckOutPax = $this->guestModel::where('out_date', $todayDate)->where('checked_out', true)
            ->where('no_of_pax','>',0)
            ->sum('no_of_pax');
            return $sumCheckOutPax;
        } else {
            $hotelDate = Carbon::parse($date->hotelDate);
            $sumCheckOutPax = $this->guestModel::where('out_date', $hotelDate)->where('checked_out', true)
            ->where('no_of_pax','>',0)
            ->sum('no_of_pax');
            return $sumCheckOutPax;
        }
    }

    public function getCountOfGroup($date)
    {
        if (!$date->hotelDate) {
            $hotelDate = Setting::select('hotel_date')->first()->hotel_date;
        } else {
            $hotelDate = Carbon::parse($date->hotelDate);
        }
        $countOfGroub = $this->guestModel::where('out_date', '>=', $hotelDate)
            ->where('in_date', '<=', $hotelDate)
            ->where('is_group', 1)
            ->where('is_checked_in',true)
            ->where('is_cancel', 0)
            ->count();

        return $countOfGroub;
    }
    public function dayUseRoom($date)
    {
        if (!$date->hotelDate) {
            $hotelDate = Setting::select('hotel_date')->first()->hotel_date;
        } else {
            $hotelDate = Carbon::parse($date->hotelDate);
        }
        $dayUseRoom = $this->guestModel::where('out_date', $hotelDate)
            ->where('in_date', $hotelDate)
            ->where('is_reservation', 0)
            ->where('is_cancel', 0)
            ->where('no_of_pax','>',0)
            ->get();

        return $dayUseRoom;
    }
    public function dayUseRoomCount($date)
    {
        $dayUseRoomCount = $this->guestModel::where('out_date', $date->hotelDate)
            ->where('in_date', $date->hotelDate)
            ->where('is_reservation', 0)
            ->where('is_cancel', 0)
            ->where('no_of_pax','>',0)
            ->count();

        return $dayUseRoomCount;
    }
    public function expectedtomorrowCount($date)   //Edit /done
    {
        //dd(is_null($date->date));
        if (!is_null($date->hotelDate)) {

            $tomorrowDate = Carbon::parse($date->hotelDate)->addDay();
            //dd('1'. $tomorrowDate);

        } else {
            $tomorrowDate = Carbon::tomorrow();
        }
        $dayUseRoomCount = $this->guestModel::where('in_date', $tomorrowDate)
            ->where('is_checked_in', 0)
            ->where('is_reservation', 1)
            ->where('is_cancel', 0)
            ->where('no_of_pax','>',0)
            ->count();

        return $dayUseRoomCount;
    }
    public function expectedtomorrowSumPax($date)  //Edit/ done
    {

        if (!is_null($date->hotelDate)) {

            $tomorrowDate = Carbon::parse($date->hotelDate)->addDay();
        } else {

            $tomorrowDate = Carbon::tomorrow();
            //dd($tomorrowDate);

        }
        $dayUseRoomCount = $this->guestModel::where('in_date', $tomorrowDate)
            ->where('is_checked_in', 0)
            ->where('is_reservation', 1)
            ->where('is_cancel', 0)
            ->where('no_of_pax','>',0)
            ->sum('no_of_pax');

        return $dayUseRoomCount;
    }
    public function expectedOuttomorrowRoomsCount($date)  //Edit / done
    {
        if (!is_null($date->hotelDate)) {

            $tomorrowDate = Carbon::parse($date->hotelDate)->addDay();
            //dd($tomorrowDate);
        } else {
            $tomorrowDate = Carbon::tomorrow();
        }
        $dayUseRoomCount = $this->guestModel::where('out_date', $tomorrowDate)
            ->where('is_cancel', 0)
            ->where('no_of_pax','>',0)
            ->count();

        return $dayUseRoomCount;
    }
    public function guestInhousPax($date)
    {
        // dd($date->hotelDate);
        if ($date->hotelDate == null) {
            $date = Carbon::today();
        } else {
            $date = Carbon::parse($date->hotelDate);
        }
        $guestInhousPax = $this->guestModel::where('in_date', '<=', $date)
            ->where('out_date', '>=', $date)
            ->where('is_cancel', 0)
            ->where('is_checked_in', 1)
            ->where('is_reservation', 0)
            ->where('no_of_pax','>',0)
            ->sum('no_of_pax');

        return $guestInhousPax;
    }
    public function guestNoOfVIP($date)
    {
        // dd($date->hotelDate);
        if ($date->hotelDate == null) {
            $date = Carbon::today();
        } else {
            $date = Carbon::parse($date->hotelDate);
        }
        $guestInhousVIP = $this->guestModel::where('in_date', '<=', $date)
            ->where('out_date', '>=', $date)
            ->where('is_cancel', 0)
            ->where('is_checked_in', 1)
            ->where('is_reservation', 0)
            ->where('vip','!=',null)
            ->where('no_of_pax','>',0)
            ->count();

        return $guestInhousVIP;
    }
    public function dayUseSumOfPax($date)
    {
        if ($date->hotelDate == null) {
            $date = Carbon::today();
        } else {
            $date = Carbon::parse($date->hotelDate);
        }

        $guestInhousPax = $this->guestModel::where('in_date', $date)
            ->where('out_date', $date)
            ->where('is_checked_in', 1)
            ->where('is_reservation', 0)
            ->where('no_of_pax','>',0)
            ->sum('no_of_pax');

        return $guestInhousPax;
    }
    public function expectedOuttomorrowSumPax($date)
    {
        //dd($date);
        if (!is_null($date->hotelDate)) {

            $tomorrowDate = Carbon::parse($date->hotelDate)->addDay();
            //dd($tomorrowDate);
        } else {
            $tomorrowDate = Carbon::tomorrow();
        }

        $expectedOuttomorrowSumPax = $this->guestModel::where('out_date', $tomorrowDate)
            ->where('is_cancel', 0)
            ->where('no_of_pax','>',0)
            ->sum('no_of_pax');

        return $expectedOuttomorrowSumPax;
    }

    public function totalFbRateromeOthersIndrate($data, $ledgerID)
    {

        $carbonDate = Carbon::parse($data->hotelDate);
        //  dd($carbonDate);
        $totalFbRateromeOthersIndrate = $this->transactionModel::where('hotel_date', $carbonDate)->whereHas('ledger', function ($q) use ($ledgerID) {
            $q->where('ledger_cat_id', $ledgerID);
        })->sum('charge_amount');
        return $totalFbRateromeOthersIndrate;
    }

    public function totalPaymants($date)
    {
        if ($date->hotelDate == null) {
            $carbonDate = Carbon::today();
        } else {
            $carbonDate = Carbon::parse($date->hotelDate);
        }
        $totalPaymants = $this->transactionModel::where('hotel_date', $carbonDate)->sum('payment_amount');
        return $totalPaymants;
    }

    public function getCountOfCompOrHousRooms($way_of_payment)
    {
        $count = $this->guestModel::where('way_of_payment', '=', $way_of_payment)
            ->where('is_reservation', '=', false)
            ->where('is_checked_in', '=', true)
            ->where('checked_out', '=', false)
            ->where('no_of_pax','>',0)
            ->count();
        return $count;
    }

    public function Calc_Of_AR_Deposit_Payment()
    {
        $hotelDate = Setting::select('hotel_date')->first()->hotel_date;
        $Calc_AR_Deposit_payment = $this->statementModel::where('void', 0)->where('trans_type', "REC")
            ->where('trans_date', $hotelDate)
            ->sum('credit_amount');
        return $Calc_AR_Deposit_payment;
    }

    public function Calc_Of_AR_Balance()
    {
        $total_AR_Balance = $this->statementModel::get()->sum(function ($q) {
            return $q->debit_amount - $q->credit_amount;
        });
        return $total_AR_Balance;
    }

    public function Count_Of_Extended_Rooms($date)
    {
        //  dd($this->extendStayModel);
        $Date = Carbon::parse($date->hotelDate);
        $extended_rooms = $this->extendStayModel::whereDate('created_at', $Date)->count();

        return  $extended_rooms;
    }

    public function Sum_Of_Extended_Pax($date)
    {
        $Date = Carbon::parse($date->hotelDate);

        $Sum_Of_ExtendStay_Pax = $this->guestModel::whereHas('extendStay', function ($q) use ($Date) {
            $q->whereDate('created_at', $Date);
        })
        ->where('no_of_pax','>',0)
        ->get()->sum('no_of_pax');
        return  $Sum_Of_ExtendStay_Pax;
    }

    public function Sum_Of_Total_Taxes($date)
    {
        $sumOfAmount = 0;
        $hotelDate = carbon::parse($date->hotelDate);
        $Total_Taxes = $this->transactionModel::with('taxes')->whereDate('hotel_date', $hotelDate)->get();

        foreach ($Total_Taxes as $tax) {
            $taxesData = $tax->taxes;
            foreach ($taxesData as $taxData) {
                $pivot = $taxData->pivot->amount;
                $sumOfAmount = $sumOfAmount + $pivot;
            }
        }

        return  $sumOfAmount;
    }


}
