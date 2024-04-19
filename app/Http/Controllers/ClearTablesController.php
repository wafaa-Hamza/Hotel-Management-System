<?php

namespace App\Http\Controllers;

use Exception;

use App\Models\Meal;
use App\Models\Room;
use App\Models\Floor;

use App\Models\Guests;
use App\Models\Ledger;
use App\Models\Market;
use App\Models\window;
use App\Models\Country;
use App\Models\Invoice;

use App\Models\RateCode;
use App\Models\RoomType;

use App\Models\LedgerCat;
use App\Models\Statement;
use App\Models\closeSales;
use App\Models\ExtendStay;
use App\Models\Maintenance;

use App\Models\MealPackage;

use App\Models\OordService;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\companyProfile;
use App\Models\DayCloseRecord;
use App\Models\SourceBusiness;
use App\Models\transactionHistory;
use Illuminate\Support\Facades\DB;
use App\Models\Room_change_history;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

class ClearTablesController extends Controller
{
    public function deleteTransaction()
    {
        $transactions = Transaction::withTrashed();
        $transactions->with('taxes')->forceDelete();

        $invoices = Invoice::with('window')->forceDelete();
        $window = Window::with('guest')->forceDelete();

        $guests = Guests::withTrashed();
        $guests->with(
            'transactions',
            'extendStay',
            'reservation_status',
            'moreName',
            'preCharges',
            'attachment',
            'ExtraBedMeal'
        )->forceDelete();


        $out_of_order_service = OordService::withTrashed();
        $out_of_order_service->forceDelete();

        $maintenances = Maintenance::withTrashed();
        $maintenances->forceDelete();

        $room = Room::where('rm_active', 1);
        $room->update([
            'fo_status' => 'VA',
            'hk_stauts' => 'CL',
        ]);


        $dayCloseRecord = DayCloseRecord::withTrashed()->forceDelete();
        $dayCloseSales = closeSales::withTrashed()->forceDelete();


        $company_profile = companyProfile::withTrashed();
        $company_profile->forceDelete();


        $companyAR_statement = Statement::withTrashed();
        $companyAR_statement->forceDelete();

        $rate_change = DB::table('ratechanges')->delete();
        $rate_change = DB::table('room_change_histories')->delete();
    }


    public function deleteMaster()
    {

        $rooms = Room::withTrashed()->forceDelete();
        $rateCodeRoomType = DB::table('ratecodes_roomtypes')->delete();
        $roomType = RoomType::withTrashed()->forceDelete();
        $floors = Floor::withTrashed()->forceDelete();

        $rateCode = RateCode::withTrashed()->forceDelete();
        $mealPackage = MealPackage::withTrashed()->forceDelete();
        $meals = Meal::withTrashed()->forceDelete();


        $marketSegment = Market::withTrashed()->forceDelete();
        $SourceBusiness = SourceBusiness::withTrashed()->forceDelete();

        $transaction = Transaction::withTrashed()->forceDelete();
        $ExtraBed = DB::table('extra_bed_or_meals')->delete();
        $ledgerCat = LedgerCat::withTrashed()->forceDelete();

        $ledgers_taxes = DB::table('ledgers_taxes')->delete();
        $ledger = Ledger::withTrashed()->forceDelete();
        dd('mmnb');
    }
}
