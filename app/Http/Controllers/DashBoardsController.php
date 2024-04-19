<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Room;
use App\Models\Guests;
use App\Models\Setting;
use App\Models\preCharge;
use App\Models\RoomStatus;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashBoardsController extends Controller
{
    public function Dashboards_Calculations()
    {

        // $this->authorize('calculations-dashboards');



        $hotelDate = Setting::select('hotel_date')->first()->hotel_date;
        $statuses = RoomStatus::all();
        $total_rooms = Room::where('rm_max_pax', '>', 0)->where('rm_active', 1)->count();
        // return $statuses;


        $vacant = Room::where('fo_status', 'VA')->where('hk_stauts', 'CL')->where('rm_max_pax', '>', 0)->where('rm_active', 1)->count();
        $vacant_per = ($vacant /  $total_rooms) * 100;


        $vacant_Status = $statuses->where('status_code', 'VACL')->map(function ($item) use ($vacant, $vacant_per) {
            $item['count'] = $vacant;
            $item['per'] = $vacant_per;
            return $item;
        });


        $vacant_dirty = Room::where('fo_status', 'VA')->where('hk_stauts', 'DI')->where('rm_max_pax', '>', 0)->where('rm_active', 1)->count();
        $vacant_dirty_per = ($vacant_dirty /  $total_rooms) * 100;
        $vacantDi_Status = $statuses->where('status_code', 'VADI')->map(function ($item) use ($vacant_dirty, $vacant_dirty_per) {
            $item['count'] = $vacant_dirty;
            $item['per'] = $vacant_dirty_per;

            return $item;
        })->flatten(1)[0];

        $occupiedCL =  Room::where('fo_status', 'OC')->where('hk_stauts', 'CL')->where('rm_max_pax', '>', 0)->where('rm_active', 1)->count();
        $occupiedCL_per = ($occupiedCL /  $total_rooms) * 100;
        $occupied_StatusCL = $statuses->where('status_code', 'OCCL')->map(function ($item) use ($occupiedCL, $occupiedCL_per) {
            $item['count'] = $occupiedCL;
            $item['per'] = $occupiedCL_per;
            return $item;
        })->flatten(1)[0];
        $occupiedDI =  Room::where('fo_status', 'OC')->where('hk_stauts', 'DI')->where('rm_max_pax', '>', 0)->where('rm_active', 1)->count();
        $occupiedDI_per = ($occupiedDI /  $total_rooms) * 100;
        $occupied_StatusDI = $statuses->where('status_code', 'OCDI')->map(function ($item) use ($occupiedDI, $occupiedDI_per) {
            $item['count'] = $occupiedDI;
            $item['per'] = $occupiedDI_per;
            return $item;
        })->flatten(1)[0];



        $blocked_clean = Room::where('fo_status', 'BL')->where('hk_stauts', 'CL')->where('rm_max_pax', '>', 0)->where('rm_active', 1)->count();
        $blocked_clean_per = ($blocked_clean /  $total_rooms) * 100;
        $blockedCl_status = $statuses->where('status_code', 'BLCL')->map(function ($item) use ($blocked_clean, $blocked_clean_per) {
            $item['count'] = $blocked_clean;
            $item['per'] = $blocked_clean_per;

            return $item;
        })->flatten(1)[0];




        $blocked_dirty = Room::where('fo_status', 'BL')->where('hk_stauts', 'DI')->where('rm_max_pax', '>', 0)->where('rm_active', 1)->count();
        $blocked_dirty_per = ($blocked_dirty /  $total_rooms) * 100;
        $blockedDi_status = $statuses->where('status_code', 'BLDI')->map(function ($item) use ($blocked_dirty, $blocked_dirty_per) {
            $item['count'] = $blocked_dirty;
            $item['per'] = $blocked_dirty_per;
            return $item;
        })->flatten(1)[0];

        $out_ofOrder = Room::where('fo_status', 'OO')->where('rm_max_pax', '>', 0)->where('rm_active', 1)->count();
        $out_ofOrder_per = ($out_ofOrder /  $total_rooms) * 100;
        $out_ofOrder_status = $statuses->where('status_code', 'OO')->map(function ($item) use ($out_ofOrder, $out_ofOrder_per) {
            $item['count'] = $out_ofOrder;
            $item['per'] = $out_ofOrder_per;
            return $item;
        })->flatten(1)[0];


        $out_ofService = Room::where('fo_status', 'OS')->where('rm_max_pax', '>', 0)->where('rm_active', 1)->count();
        $out_ofService_per = ($out_ofService /  $total_rooms) * 100;
        $out_ofService_status = $statuses->where('status_code', 'OS')->map(function ($item) use ($out_ofService, $out_ofService_per) {
            $item['count'] = $out_ofService;
            $item['per'] = $out_ofService_per;
            return $item;
        })->flatten(1)[0];



        $occupied = $occupiedDI + $occupiedCL;

        $occupancy = ($total_rooms <= 0 ? 0 : ($occupied /  $total_rooms) * 100);




        // dd($avg_room_rate );

        $total_room_rate_now = Transaction::join('ledgers', 'ledgers.id', 'transactions.ledger_id')->whereDate('hotel_date', $hotelDate)
            ->where('ledgers.ledger_cat_id', 1)->sum('charge_amount');

        $avg_room_rate = ($occupied <= 0 ? 0 : $total_room_rate_now / $occupied);

        $total_FB_now = Transaction::join('ledgers', 'ledgers.id', 'transactions.ledger_id')->whereDate('hotel_date', $hotelDate)
            ->where('ledgers.ledger_cat_id', 2)->sum('charge_amount');

        $tot_payment_received = Transaction::whereDate('hotel_date',  $hotelDate)->sum('payment_amount');

        $total_room_rate_pre = preCharge::whereDate('hotel_date', $hotelDate)->join('ledgers', 'ledgers.id', 'pre_charges.ledger_id')
            ->where('ledgers.id', 1)->sum('amount');
        // dd($total_room_rate_today);

        $total_room_rate_today = $total_room_rate_pre + $total_room_rate_now;

        $total_revenue_pre = preCharge::whereDate('hotel_date', $hotelDate)->sum('amount');

        $total_revenue_today = $total_revenue_pre + $total_FB_now;

        $number_of_res = Guests::whereDate('created_at', $hotelDate)->where('is_reservation', 1)->count();
        $number_of_checkIn = Guests::whereDate('created_inhousGuest_at', $hotelDate)->where('is_checked_in', 1)->count();

        $expected_arrival = Guests::whereDate('in_date',         $hotelDate)->where('is_reservation', 1)
            ->where('is_checked_in', 0)->where('is_cancel', 0)->count();

        $expected_arrival_total = Guests::whereDate('in_date',         $hotelDate)
            ->where('is_cancel', 0)->count();

        $expected_arrival_checked = Guests::whereDate('in_date',         $hotelDate)
            ->where('is_checked_in', 1)->where('is_cancel', 0)->count();

        $inhouse_count = Guests::whereDate('in_date', '<=', $hotelDate)->whereDate('out_date', '>=', $hotelDate)
            ->where('is_checked_in', 1)->where('is_cancel', 0)->count();


        $expected_out = Guests::whereDate('out_date', $hotelDate)->where('is_reservation', 0)

            ->where('is_checked_in', 1)->count();

        $expected_out_total = Guests::whereDate('out_date', $hotelDate)->where('is_reservation', 0)
            ->count();
        $expected_out_out = Guests::whereDate('out_date', $hotelDate)->where('is_reservation', 0)->where('is_checked_in', 0)
            ->where('checked_out', 1)
            ->count();

        $pax_arrival = Guests::whereDate('in_date',         $hotelDate)->where('is_reservation', 1)
            ->where('is_checked_in', 0)->where('is_cancel', 0)->sum('no_of_pax');
        $pax_inhouse = Guests::whereDate('in_date', '<=', $hotelDate)->whereDate('out_date', '>=', $hotelDate)
            ->where('is_checked_in', 1)->where('is_cancel', 0)->sum('no_of_pax');
        $pax_out = Guests::whereDate('out_date', $hotelDate)->where('is_reservation', 0)
            ->where('is_checked_in', 1)->sum('no_of_pax');



        $Calculations = [
            "occupancy" => $occupancy,
            "avg_room_rate" => $avg_room_rate,
            "tot_payment_received" => $tot_payment_received,
            "total_room_rate_today" => $total_room_rate_today,
            "total_revenue_today" => $total_revenue_today,
            "number_of_res" => $number_of_res,
            "number_of_checkIn" => $number_of_checkIn,
            "expected_arrival" => $expected_arrival,
            "expected_out" => $expected_out,
            "total_rooms" => $total_rooms,
            "expected_arrival_total" => $expected_arrival_total,
            "expected_arrival_checked" => $expected_arrival_checked,
            "inhouse_count" => $inhouse_count,
            "expected_out_total" => $expected_out_total,
            "expected_out_out" => $expected_out_out,
            "pax_arrival" => $pax_arrival,
            "pax_inhouse" => $pax_inhouse,
            "pax_out" => $pax_out





        ];
        $Dashboards_Calculations = [
            "Status" => [
                $vacant_Status[0],
                $vacantDi_Status,
                $occupied_StatusCL,
                $occupied_StatusDI,
                $blockedCl_status,
                $blockedDi_status,
                $out_ofOrder_status,
                $out_ofService_status,
            ],
            "Calculations" => $Calculations,
        ];



        return response()->json($Dashboards_Calculations);
    }
}
