<?php

namespace App\Repository\Integration;

use App\helpers;
use Carbon\Carbon;
use App\Models\Room;
use App\Models\Guests;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Profiler\Profile;
use App\Repositoryinterface\Integration\IntegrationForNTInterface;

class DBrepositoryNTMPIntegration implements IntegrationForNTInterface
{
    use helpers;
    private $guestModel;
    private $roomModel;
    private $transactionModel;
    private $guestInhouseModel;
    public function  __construct(Guests $guestModel, Room $roomModel,
     Transaction $transactionModel,Guests $guestInhouseModel)
    {
        $this->guestModel = $guestModel;
        $this->roomModel = $roomModel;
        $this->transactionModel = $transactionModel;
        $this->guestInhouseModel = $guestInhouseModel;
    }
    
   /**
    * NTMPCreateOrUpdate($request)
    */ 
    public function NTMPCreateOrUpdate($guest_id, $cuFlag, $transactionTypeId = null,$isUpdate = null)
    {
        $guestData = $this->guestModel->where('id', $guest_id)->with('profile:id,id_no,gender')->with('roomType:id,scth_type_code')->with("Profile.country")
            ->first();
        $request = new Request;
        $totalDurationDays =  $this->NTMPhandelDateForNTMP($guestData->out_date) - $this->NTMPhandelDateForNTMP($guestData->in_date);
        $totalRoomRate =  $guestData->rm_rate * $totalDurationDays;
        $municipalityTax = $totalRoomRate * (2.5 / 100);
        $vat = $municipalityTax + $totalRoomRate;
        $dateOfPearth = $this->NTMPhandelDateForNTMP($guestData->profile->date_of_birth);
        // dd($guestData->scth_transaction_id);
        $GuestDataForNTMPArr =
            [
                "bookingNo" =>  "$guestData->id",
                "nationalityCode" => $guestData->Profile->country->country_code,
                "checkInDate" =>  $this->NTMPhandelDateForNTMP($guestData->in_date),
                "checkOutDate" =>  $this->NTMPhandelDateForNTMP($guestData->out_date),
                "totalDurationDays" => $totalDurationDays,
                "allotedRoomNo" => ($guestData->room_id == null) ? 1 : $guestData->room_id,
                "roomRentType" => "1", //from request
                "dailyRoomRate" => $guestData->rm_rate, //rmRate for first day in guest **********
                "totalRoomRate" => "$totalRoomRate", //(rmRate for first day in guest * totalDurationDays)
                "vat" => $vat, //(municipalityTax + totalRoomRate) 15%
                "municipalityTax" => $municipalityTax, //totalRoomRate * 2.5%
                "discount" => "0", //0
                "grandTotal" =>  $municipalityTax + $vat +  $totalRoomRate, //municipalityTax + vat + totalRoomRate
                "transactionTypeId" => "$transactionTypeId", // if in function booking will be 1
                "gender" => $this->NTMPGender($guestData->profile->gender), //if meal 1 if femail 2 not found 0 //get it from guest_profile
                "transactionId" => $guestData->scth_transaction_id, // add it in guest called scth_transaction_id nullable; null in first booking and put it in update and check in and check out
                "checkInTime" => "0", //defalt = 0 ;guest guest_inhous_guest_at convert it to dateTime and put time as Hms put it in check_in function nly 
                "checkOutTime" => "0", // defult =0 with check out function only
                "customerType" => $guestData->customer_type, //add customerType as column in guest validatio from 1 to 4
                "noOfGuest" => $guestData->no_of_pax, // no of pax
                "roomType" => $guestData->roomType->scth_type_code, //$guestData->roomType->scth_type_code
                "purposeOfVisit" => $guestData->purpose_of_visit, //add in table guest from 1 to 10
                "dateOfBirth" => "$dateOfPearth", //from profile 
                "paymentType" => $this->NTMPWayOfPayment($guestData->way_of_payment), // wayOfBayment from guest
                "noOfRooms" => "1", //from payment column called 
                "cuFlag" => "$cuFlag",
                // "userId" => "10006522",
                "userId" => "38071541000097",
                "channel" => "Masasoft"
            ];
        $request->merge($GuestDataForNTMPArr);
        $expectedAuthString = base64_encode("38071541000097" . ":" .  'MpViRPjziU');
        if($isUpdate)
        {
        

        }else{
            $responsFromNTMP = Http::withBody(json_encode($GuestDataForNTMPArr), 'application/json')
            ->withOptions([
                // 'headers' => ["Content-Type" => "application/json", "X-Gatewa-APIKey" => "a17b2d02-5915-4e0b-9ae1-2a449af00ac9", "username" => " 38071541000097","password"=> 'MpViRPjziU']
                'headers' => ["Content-Type" => "application/json", "X-Gatewa-APIKey" => "a17b2d02-5915-4e0b-9ae1-2a449af00ac9", 'Authorization' => 'Basic ' . $expectedAuthString]
            ])->post("https://api-stg.ntmp.gov.sa/gateway/CreateOrUpdateBooking/1.0/createOrUpdateBooking");

return $responsFromNTMP;

        }
       
    }
    public function NTMPhandelDateForNTMP($request)
    {
        $date = Carbon::parse("$request")->format('Ymd');
        return $date;
    }
    public function NTMPhandelTimeForNTMP($time)
    {
        $time = Carbon::parse($time)->format('His');
        return $time;
    }
    public function NTMPGender($gender)
    {

        if ($gender == 'male') {
            return 1;
        } elseif ($gender == 'female') {
            return 2;
        } else {
            return 0;
        }
    }


    public function NTMPWayOfPayment($wayOfPayment)
    {

        if ($wayOfPayment == 'CASH') {
            return 1;
        } elseif ($wayOfPayment == 'CARD') {
            return 2;
        } elseif ($wayOfPayment == 'CL') {
            return 3;
        } else {
            return 5;
        }
    }

    /**
     * cancelBooking($request)
     */
    public function NTMPCancelBooking($guest_id, $cancelReson = 0)
    {
        $guestData = $this->guestModel->where('id', $guest_id)->with('profile:id,id_no,gender')->with('roomType:scth_type_code')
            ->first();
        $totalDurationDays =  $this->NTMPhandelDateForNTMP($guestData->in_date) - $this->NTMPhandelDateForNTMP($guestData->out_date);
        $totalRoomRate =  $guestData->rm_rate * $totalDurationDays;
        $municipalityTax = $totalRoomRate * (2.5 / 100);
        $vat = $municipalityTax + $totalRoomRate;
        // $dd =  $this->NTMPcancelReason([$cancelReson]);
        //  dd($dd);
        $request = new Request;
        $GuestDataForNTMPArr =
            [
                "transactionId" => $guestData->scth_transaction_id, // add it in guest called scth_transaction_id nullable; null in first booking and put it in update and check in and check out
                "cancelReason" => $cancelReson,
                "cancelWithCharges" => false,
                "chargeableDays" => "0",
                "roomRentType" => "0",
                "dailyRoomRate" => "0",
                "totalRoomRate" => "0",
                "vat" => "0",
                "municipalityTax" => "0",
                "discount" => "0",
                "grandTotal" => "0",
                "paymentType" => $this->NTMPWayOfPayment($guestData->way_of_payment),
                "cuFlag" => "1",
                "userId" => "38071541000097",
                "channel" => "Masasoft"
            ];

        $request->merge($GuestDataForNTMPArr);
        $expectedAuthString = base64_encode("38071541000097" . ":" .  'MpViRPjziU');
        $responsFromNTMP = Http::withBody(json_encode($GuestDataForNTMPArr), 'application/json')
            ->withOptions([
                // 'headers' => ["Content-Type" => "application/json", "X-Gatewa-APIKey" => "a17b2d02-5915-4e0b-9ae1-2a449af00ac9", "username" => " 38071541000097","password"=> 'MpViRPjziU']
                'headers' => ["Content-Type" => "application/json", "X-Gatewa-APIKey" => "a17b2d02-5915-4e0b-9ae1-2a449af00ac9", 'Authorization' => 'Basic ' . $expectedAuthString]
            ])->post("https://api-stg.ntmp.gov.sa/gateway/CancelBooking/1.0/cancelBooking");
        return $responsFromNTMP;
    }

    public function NTMPcancelReason($cancelReson = 0)
    {
        //dd($request['cancelReson']);
        $allCancelReson = [
            "0" => ($cancelReson == "No Reason") ? "No Reason" :  "Not applicable",
            "Cancelled by Customer",
            "Cancelled by Hotel",
            "Customer Find better deal",
            "Customer was not satisfied",
            "Customer does not arrived on given date",
            "Customer changed the dates",
        ];
        $valueExist = array_search($cancelReson['cancelReson'], $allCancelReson);
        if ($valueExist) {
            return $valueExist;
        } else {
            return 8;
        }
    }

    /**
     * NTMPExpensesDetails
     */

    public function NTMPExpensesDetails($guest_id)
    {
        $guestData = $this->guestModel->where('id', $guest_id)
            ->with('transactions.ledger')
            //->with('profile:id,id_no,gender')->with('roomType:scth_type_code')
            ->first();

        $transtunitPrice = $guestData->transactions->first();//->charge_without_taxes;
        if($transtunitPrice){
           $unitPrice = $transtunitPrice->charge_without_taxes;
        }else{
            $unitPrice = 0;
        }
        // $totalDurationDays =  $this->NTMPhandelDateForNTMP($guestData->in_date) - $this->NTMPhandelDateForNTMP($guestData->out_date);
        //$totalRoomRate =  $guestData->rm_rate * $totalDurationDays;
        $municipalityTax = $unitPrice * (2.5 / 100);
        $vat = ($unitPrice * 15) / 100;
        $grandTotal =  $unitPrice +  $vat + $municipalityTax;
        //$dd =  $this->NTMPcancelReason(["cancelReson"=>"Customer Find br deal"]);
        //  dd($dd);
        $request = new Request;
        $GuestDataForNTMPArr =
            [
                "TransactionId" => $guestData->scth_transaction_id, // add it in guest called scth_transaction_id nullable; null in first booking and put it in update and check in and check out
                "channel" => "Masasoft",
                "ExpenseItems" => [
                    "ExpenseDate" => $this->NTMPhandelDateForNTMP($guestData->transactions->first()->hotel_date),
                    "ItemNumber" => $guestData->transactions->first()->trans_no,
                    "ExpenseTypeId" => $guestData->transactions->first()->ledger->scth_ledger_id,
                    "unitPrice" =>  $unitPrice,
                    "discount" => "0",
                    "vat" => $vat,
                    "municipalityTax" => $municipalityTax,
                    "grandTotal" => $grandTotal,
                    "paymentType" => $this->NTMPWayOfPayment($guestData->way_of_payment),
                    "cuFlag" => "1",
                ],
                "expenseDate" => $guestData->transactions->first()->hotel_date,
                "itemNumber" => $guestData->transactions->first()->trans_no,
                "expenseTypeId" => $guestData->transactions->first()->ledger->scth_ledger_id,
                "unitPrice" => $unitPrice,
                "discount" => "0",
                "vat" => $vat,
                "municipalityTax" =>  $municipalityTax,
                "grandTotal" =>  $grandTotal,
                "paymentType" =>  $this->NTMPWayOfPayment($guestData->way_of_payment),
                "cuFlag" => "1",
            ];


        $request->merge($GuestDataForNTMPArr);
        $expectedAuthString = base64_encode("38071541000097" . ":" .  'MpViRPjziU');
        $responsFromNTMP = Http::withBody(json_encode($GuestDataForNTMPArr), 'application/json')
            ->withOptions([
                // 'headers' => ["Content-Type" => "application/json", "X-Gatewa-APIKey" => "a17b2d02-5915-4e0b-9ae1-2a449af00ac9", "username" => " 38071541000097","password"=> 'MpViRPjziU']
                'headers' => ["Content-Type" => "application/json", "X-Gatewa-APIKey" => "a17b2d02-5915-4e0b-9ae1-2a449af00ac9", 'Authorization' => 'Basic ' . $expectedAuthString]
            ])->post("https://api-stg.ntmp.gov.sa/gateway/BookingExpense/1.0/bookingExpense");
        return $responsFromNTMP;
    }

    /**
     * NTMPOccupancyUpdate
     */
    public function NTMPOccupancyUpdate($request)
    {
        $hotelDate = $this->settings()->first()->hotel_date;

        $roomsOccupied = $this->roomModel->where('fo_status', ' OC')->orWhere('fo_status', 'DO')->count();
        $roomsAvailable = $this->roomModel->where('fo_status', ' VA')->count();
        $roomsBooked = $this->roomModel->where('fo_status', ' BL')->count();
        $roomsOnMaintenance = $this->roomModel->where('fo_status', ' OO')->orWhere('fo_status', 'OS')->count();
        $totalAdults = $this->guestModel->where('is_checked_in', 1)->where('checked_out', 0)->sum('no_of_pax');
        $totalRevenue = $this->transactionModel->whereDate('hotel_date', now())->sum('charge_amount');
        // dd( $totalRevenue);

        $request = new Request;

        $DataForNTMPArr =
            [
                "updateDate" => $this->NTMPhandelDateForNTMP($hotelDate),
                "roomsOccupied" =>  $roomsOccupied,
                "roomsAvailable" => $roomsAvailable,
                "roomsBooked" => $roomsBooked,
                "roomsOnMaintenance" =>  $roomsOnMaintenance,
                // "transactionId"=> "101",
                "dayClosing" => "true",
                "totalRooms" => "90",
                "totalAdults" =>  $totalAdults,
                "totalChildren" => "0",
                "totalGuests" => $totalAdults,
                "totalRevenue" => $totalRevenue,
                //"userId" => "38071541000097",
                "userId" => "masasoft",
                "channel" => "Masasoft"
            ];
        $request->merge($DataForNTMPArr);

        $expectedAuthString = base64_encode("masasoft" . ":" .  'M@$as0ft');
        //dd(json_encode($DataForNTMPArr));

        $responsFromNTMP = Http::withBody(json_encode($DataForNTMPArr), 'application/json')
            ->withOptions([
                // 'headers' => ["Content-Type" => "application/json", "X-Gatewa-APIKey" => "a17b2d02-5915-4e0b-9ae1-2a449af00ac9", "username" => " 38071541000097","password"=> 'MpViRPjziU']
                'headers' => ["Content-Type" => "application/json", "X-Gatewa-APIKey" => "dbf2f884-f865-4448-affe-9ec162365133", 'Authorization' => 'Basic ' . $expectedAuthString]
            ])->post("https://api-stg.ntmp.gov.sa/gateway/OccupancyUpdate/2.0/occupancyUpdate");
        return $responsFromNTMP;
    }
}
