<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use App\Models\Room;
use App\Models\Guests;
use App\Models\Invoice;
use App\Models\Reports;
use App\Models\LedgerCat;
use App\Models\preCharge;
use App\Models\Ratechange;
use App\Models\OordService;
use App\Models\PaymentMode;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\guest_profile;
use App\Models\ChartOfAccount;

use App\Models\DayCloseRecord;
use Illuminate\Support\Carbon;
use App\Models\ChartOfAccounts;
use App\Models\RoomStatusHistory;
use Illuminate\Support\Facades\DB;
use App\Models\Room_change_history;
use App\Models\JournalVoucherMaster;

use Spatie\Multitenancy\Models\Tenant;
use App\Repositoryinterface\Generalinterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryInvoiceInterface;



//use CarbonPeriod

class ReportsController extends Controller
{
    private $generalInterface;
    private $invoiceInterface;
    public function __construct(Generalinterface $generalInterface, RepositoryInvoiceInterface $invoiceInterface)
    {
        $this->generalInterface = $generalInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function Tot_Payment_Of_Cashier_Closer(Request  $request)
    {
        if ($request->user_id == null) {
            $Total_Closer_Payment = Transaction::whereBetween('hotel_date', [$request->Date_From, $request->Date_To])->get();
        } else {

            $Total_Closer_Payment = Transaction::with('created_by')->where('created_by', $request->user_id)
                ->whereBetween('hotel_date', [$request->Date_From, $request->Date_To])->get();
        }
        return response()->json(['Total_Closer_Payment' => $Total_Closer_Payment]);
    }

    public function Tot_Payment_Of_Cashier_Summary(Request $request)
    {
        if ($request->user_id == null) {
            $Total_Summary_Payment = Transaction::with('payment_type')->whereNot('payment_type_id', null)
                ->whereBetween('hotel_date', [$request->Date_From, $request->Date_To])
                ->select('payment_type_id', DB::raw('SUM(payment_amount) as total_payment_amount'))->groupBy('payment_type_id')
                ->get();
        } else {

            $Total_Summary_Payment = Transaction::with('payment_type')->with('created_by')
                ->where('created_by', $request->user_id)->whereNot('payment_type_id', null)
                ->whereBetween('hotel_date', [$request->Date_From, $request->Date_To])
                ->select('payment_type_id', 'created_by', DB::raw('SUM(payment_amount) as total_payment_amount'))
                ->groupBy('payment_type_id', 'created_by')
                ->get();
        }
        return response()->json(['Total_Summary_Payment' => $Total_Summary_Payment]);
    }

    public function ProductivityByCompany(Request $request)
    {
        //     $check=Transaction::lastMonth();
        //     dd($check);

        //     $check=Transaction::last7Days();
        //     dd($check);

        //  $check=Transaction::monthToDate($request->input('date'));

        //  dd($check);

        //  $check=Transaction::lastMonthToDate($request->input('date'));

        //  dd($check);

        //  $check=Transaction::yearToDate($request->input('date'));

        //  dd($check);
        //  $check=Transaction::lastYearToDate($request->input('date'));

        //  dd($check);

        //  $check=Transaction::month($request->input('date'));


        //  dd($check);


        $ProductivityByCompany = Guests::with('company')
            ->whereDate('in_date', '<=', $request->start_date)
            ->whereDate('out_date', '>=', $request->end_date)
            ->join('transactions', 'transactions.guest_id', 'guests.id')
            ->join('ledgers', 'ledgers.id', 'transactions.ledger_id')
            ->selectRaw('SUM(CASE WHEN ledgers.ledger_cat_id = "1"  THEN charge_amount ELSE 0 END) AS Total_Room_Rate,
             SUM(CASE WHEN ledgers.ledger_cat_id = "2" THEN charge_amount ELSE 0 END) AS FB,
             SUM(CASE WHEN ledgers.ledger_cat_id = "1"  THEN charge_amount ELSE 0 END)/COUNT(company_id)  as Avg_Room_Rate,
             SUM(CASE WHEN ledgers.ledger_cat_id = "1"  THEN charge_amount ELSE 0 END)/sum(no_of_pax)  as Avg_Room_Rate_Per_Pax,
             SUM(CASE WHEN ledgers.ledger_cat_id = "2"  THEN charge_amount ELSE 0 END)/COUNT(company_id)  as Avg_FB,
             SUM(CASE WHEN ledgers.ledger_cat_id = "2"  THEN charge_amount ELSE 0 END)/sum(no_of_pax)  as Avg_FB_Per_Pax')
            ->selectRaw('sum(no_of_pax) as no_of_pax, company_id')
            ->selectRaw('sum(no_of_nights) as no_of_nights, company_id')
            ->selectRaw('COUNT(company_id) as Rooms, company_id')->groupBy('company_id')
            ->get();

        return response()->json($ProductivityByCompany);
    }
    public function ProductivityByCountry(Request $request)
    {

        $ProductivityByCountry = guest_profile::with('country')
            ->join('guests', 'guests.profile_id', 'guest_profiles.id')
            ->whereDate('in_date', '<=', $request->start_date)
            ->whereDate('out_date', '>=', $request->end_date)

            ->join('transactions', 'transactions.guest_id', 'guests.id')
            ->join('ledgers', 'ledgers.id', 'transactions.ledger_id')
            ->selectRaw('SUM(CASE WHEN ledgers.ledger_cat_id = "1"  THEN charge_amount ELSE 0 END) AS Total_Room_Rate ,
             SUM(CASE WHEN ledgers.ledger_cat_id = "2" THEN charge_amount ELSE 0 END) AS  FB,
             SUM(CASE WHEN ledgers.ledger_cat_id = "1"  THEN charge_amount ELSE 0 END)/COUNT(country_id)  as Avg_Room_Rate,
             SUM(CASE WHEN ledgers.ledger_cat_id = "1"  THEN charge_amount ELSE 0 END)/sum(no_of_pax)  as Avg_Room_Rate_Per_Pax,
             SUM(CASE WHEN ledgers.ledger_cat_id = "2"  THEN charge_amount ELSE 0 END)/COUNT(country_id)  as Avg_FB,
             SUM(CASE WHEN ledgers.ledger_cat_id = "2"  THEN charge_amount ELSE 0 END)/sum(no_of_pax)  as Avg_FB_Per_Pax ')


            ->selectRaw('sum(no_of_pax) as no_of_pax, country_id')
            ->selectRaw('sum(no_of_nights) as no_of_nights, country_id')
            ->selectRaw('COUNT(country_id) as Rooms, country_id')->groupBy('country_id')
            ->get();
        return response()->json($ProductivityByCountry);
    }

    public function ProductivityBySource_Business(Request $request)
    {
        $ProductivityBySource_Business = Guests::with('SourceBusiness')
            ->whereDate('in_date', '<=', $request->start_date)
            ->whereDate('out_date', '>=', $request->end_date)

            ->join('transactions', 'transactions.guest_id', 'guests.id')
            ->join('ledgers', 'ledgers.id', 'transactions.ledger_id')
            ->selectRaw('SUM(CASE WHEN ledgers.ledger_cat_id = "1"  THEN charge_amount ELSE 0 END) AS Total_Room_Rate ,
             SUM(CASE WHEN ledgers.ledger_cat_id = "2" THEN charge_amount ELSE 0 END) AS  FB,
             SUM(CASE WHEN ledgers.ledger_cat_id = "1"  THEN charge_amount ELSE 0 END)/COUNT(source_of_business_id)  as Avg_Room_Rate,
             SUM(CASE WHEN ledgers.ledger_cat_id = "1"  THEN charge_amount ELSE 0 END)/sum(no_of_pax)  as Avg_Room_Rate_Per_Pax,
             SUM(CASE WHEN ledgers.ledger_cat_id = "2"  THEN charge_amount ELSE 0 END)/COUNT(source_of_business_id)  as Avg_FB,
             SUM(CASE WHEN ledgers.ledger_cat_id = "2"  THEN charge_amount ELSE 0 END)/sum(no_of_pax)  as Avg_FB_Per_Pax ')

            ->selectRaw('sum(no_of_pax) as no_of_pax, source_of_business_id')
            ->selectRaw('sum(no_of_nights) as no_of_nights, source_of_business_id')
            ->selectRaw('COUNT(source_of_business_id) as Rooms, source_of_business_id')->groupBy('source_of_business_id')
            ->get();

        return response()->json($ProductivityBySource_Business);
    }
    public function ProductivityByMarket_Segments(Request $request)
    {

        $arr = [];
        $startDate = Carbon::parse($request['start_date']);
        $endDate = Carbon::parse($request['end_date']);
        for ($dateLoop = $startDate; $dateLoop->lte($endDate); $dateLoop->addDay()) {
            $Day_Data = $dateLoop->format('Y-m-d');
            // dd($Day_Data);
            $ProductivityByMarket_Segments = Guests::with('marketSegment')


                ->join('transactions', 'transactions.guest_id', 'guests.id')
                ->selectRaw('DATE_FORMAT(transactions.hotel_date, "%Y-%m-%d") as Date')
                ->groupBy('Date')
                ->where('transactions.hotel_date', $Day_Data)
                ->join('ledgers', 'ledgers.id', 'transactions.ledger_id')
                ->selectRaw('SUM(CASE WHEN ledgers.ledger_cat_id = "1"  THEN charge_amount ELSE 0 END) AS Total_Room_Rate ,
             SUM(CASE WHEN ledgers.ledger_cat_id = "2" THEN charge_amount ELSE 0 END) AS  FB,
             SUM(CASE WHEN ledgers.ledger_cat_id = "1"  THEN charge_amount ELSE 0 END)/COUNT(market_segment_id)  as Avg_Room_Rate,
             SUM(CASE WHEN ledgers.ledger_cat_id = "1"  THEN charge_amount ELSE 0 END)/sum(no_of_pax)  as Avg_Room_Rate_Per_Pax,
             SUM(CASE WHEN ledgers.ledger_cat_id = "2"  THEN charge_amount ELSE 0 END)/COUNT(market_segment_id)  as Avg_FB,
             SUM(CASE WHEN ledgers.ledger_cat_id = "2"  THEN charge_amount ELSE 0 END)/sum(no_of_pax)  as Avg_FB_Per_Pax ')

                ->selectRaw('sum(no_of_pax) as no_of_pax, market_segment_id')
                ->selectRaw('sum(no_of_nights) as no_of_nights, market_segment_id')
                ->selectRaw('COUNT(market_segment_id) as Rooms, market_segment_id')->groupBy('market_segment_id')
                ->get();

            array_push($arr, $ProductivityByMarket_Segments);
        }
        return response()->json([
            'ProductivityByMarket_Segments' => $arr
        ]);
    }
    public function Guest_Ledger_Report(Request $request)
    {

        $Guest_ledger_charges = LedgerCat::with(['ledgers' => function ($q) {
            $q->with(['transactions' => function ($query) {
                $query->selectRaw('sum(charge_amount) as sum_charge_amount,ledger_id');
                $query->where('is_void', 0);
                $query->groupBy('ledger_id');
            }]);
        }])
            ->with(['transactionsLedCat' => function ($qu) use ($request) {
                $qu->selectRaw('sum(charge_amount) as sum_charge_amount,ledger_cat_id');
                $qu->where('is_void', 0);
                $qu->groupBy('ledger_cat_id');
                $qu->where('hotel_date', $request->date);
            }])->get();

        $Guest_ledger_payments = PaymentMode::with(['payment_types' => function ($q) {
            $q->with(['transactions' => function ($query) {
                $query->selectRaw('sum(payment_amount) as sum_payment_amount,payment_type_id');
                $query->where('is_void', 0);
                $query->groupBy('payment_type_id');
            }]);
        }])
            ->with(['transactions' => function ($que) use ($request) {
                $que->selectRaw('sum(payment_amount) as sum_payment_amount,payment_modes_id');
                $que->where('is_void', 0);
                $que->groupBy('payment_modes_id');
                $que->where('hotel_date', $request->date);
            }])
            ->get();
        $return_amounts = [
            'Guest_ledger_charges'  => $Guest_ledger_charges,
            'Guest_ledger_payments' => $Guest_ledger_payments
        ];

        return $return_amounts;
    }
    public function Voided_Transactions(Request $request)
    {
        $voided_transaction = Transaction::with(['guest', 'created_by', 'room'])
            ->where('is_void', 1)
            ->whereDate('voided_at', '>=', $request->start_date)
            ->whereDate('voided_at', '<=', $request->end_date)
            ->get();

//        $tot_sum_charge = Transaction::where('is_void', 1)
//            ->whereDate('voided_at', '>=', $request->start_date)
//            ->whereDate('voided_at', '<=', $request->end_date)
//            ->sum('charge_amount');

        return response()->json([
            'data' => [
                'voided_transaction' => $voided_transaction,
                'tot_sum_charge'     => 0//$tot_sum_charge
            ]
        ]);
    }
    public function Transactions_Details(Request $request)
    {
        $ledger_id = [];
        $ledger_id = $request->ledger_id;
        $transactions_details = Transaction::with(['guest.profile', 'created_by', 'room'])
            ->where('is_void', 0)
            ->whereDate('hotel_date', '>=', $request->start_date)
            ->whereDate('hotel_date', '<=', $request->end_date)
            ->whereIn('ledger_id', $ledger_id)
            ->where('ledger_id', '!=', null)
            ->get();

        $tot_sum_charge = Transaction::where('is_void', 0)
            ->whereDate('hotel_date', '>=', $request->start_date)
            ->whereDate('hotel_date', '<=', $request->end_date)
            ->whereIn('ledger_id', $ledger_id)
            ->where('ledger_id', '!=', null)->sum('charge_amount');
        return response()->json([
            'data' => [
                'transactions_details' => $transactions_details,
                'tot_sum_charge'       => $tot_sum_charge,
            ]
        ]);
    }
    public function Transactions_Details_By_User(Request $request)
    {
        $ledger_id = [];
        $ledger_id = $request->ledger_id;
        $transactions_details_by_user = Transaction::with(['guest.profile', 'created_by', 'room'])
            ->where('created_by', $request->user_id)
            ->when($request->has('ledger_id'), function ($query) use ($ledger_id) {
                return $query->whereIn('ledger_id', $ledger_id);
            })
            ->where('is_void', 0)
            ->whereDate('hotel_date', '>=', $request->start_date)
            ->whereDate('hotel_date', '<=', $request->end_date)
            ->get();

        $tot_sum_charge = Transaction::where('created_by', $request->user_id)
            ->when($request->has('ledger_id'), function ($query) use ($ledger_id) {
                return $query->whereIn('ledger_id', $ledger_id);
            })
            ->where('is_void', 0)
            ->whereDate('hotel_date', '>=', $request->start_date)
            ->whereDate('hotel_date', '<=', $request->end_date)
            ->sum('charge_amount');
        return response()->json([
            'data' => [
                'transactions_details_by_user' => $transactions_details_by_user,
                'tot_sum_charge'               => $tot_sum_charge,
            ]
        ]);
    }
    public function InHouse_Guests_Report(Request $request)
    {
        $inHouse_guests_report = Guests::with('profile', 'rateCode', 'room')
            ->WhereBetween('in_date', [$request->startDate, $request->endDate])
            ->orWhereBetween('out_date', [$request->startDate, $request->endDate])
            ->orWhereDate('in_date', '>=', $request->startDate)
            ->WhereDate('out_date', '<=', $request->endDate)
            ->get();

        return response()->json([
            'inHouse_guests_report' => $inHouse_guests_report
        ]);
    }
    public function Room_Change_Report(Request $request)
    {
        $room_change_report = Room_change_history::with('user', 'RoomChangeFrom', 'RoomChangeTo')
            ->WhereBetween('hotel_date', [$request->startDate, $request->endDate])
            ->get();
        return response()->json([
            'room_change_report' => $room_change_report
        ]);
    }

    public function Rate_Change_Report(Request $request)
    {
        $rate_change_report = Ratechange::with('users', 'RateCodeFrom', 'RateCodeTo')
            ->WhereBetween('hotel_date', [$request->startDate, $request->endDate])
            ->get();
        return response()->json([
            'rate_change_report' => $rate_change_report
        ]);
    }
    public function Actual_CheckedIn_And_Arrivals_Report(Request $request)
    {
        $Actual_CheckedIn_And_Arrivals_Report = Guests::with('profile', 'room', 'rateCode')
            ->whereBetween('in_date', [$request->startDate, $request->endDate])
            ->get();

        return response()->json(['Actual_CheckedIn_And_Arrivals_Report' => $Actual_CheckedIn_And_Arrivals_Report]);
    }
    public function Checked_Out_And_Departure_Report(Request $request)
    {
        $Checked_Out_And_Departure_Report = Guests::with('profile', 'room', 'checkout_by')
            ->whereBetween('out_date', [$request->startDate, $request->endDate])
            ->get();
        return response()->json(['Checked_Out_And_Departure_Report' => $Checked_Out_And_Departure_Report]);
    }

    public function Revenue_By_Dates_Report(Request $request)
    {
        return $this->Revenue_By_Dates_by_ledgerCat($request);
    }
    private function revenueByCatByDate($date, $cat_id)
    {
        $sumData = transaction::join('ledgers', 'transactions.ledger_id', 'ledgers.id')
            ->where('transactions.is_void', 0)
            ->where('ledgers.ledger_cat_id', $cat_id)
            ->whereDate('hotel_date', $date)->sum('charge_amount');
        return $sumData;
    }
    public function Revenue_By_Dates_by_ledgerCat($request)
    {
        $returnArr = [];
        $roomRev = 0;
        $FB = 0;
        $Com = 0;
        $Lun = 0;
        $other = 0;
        $total = 0;
        //dd($request['endDate']);
        $startDate = Carbon::parse($request['startDate']);
        $endDate = Carbon::parse($request['endDate']);

        for ($dateLoop = $startDate; $dateLoop->lte($endDate); $dateLoop->addDay()) {
            $roomRev = $this->revenueByCatByDate($dateLoop, 1);
            $FB = $this->revenueByCatByDate($dateLoop, 2);
            $Com = $this->revenueByCatByDate($dateLoop, 3);
            $Lun = $this->revenueByCatByDate($dateLoop, 4);
            $other = $this->revenueByCatByDate($dateLoop, 5);
            $total = $roomRev + $FB + $Com + $Lun + $other;

            $Revenue_By_Dates = [
                'date' => $dateLoop->format('Y-m-d'),
                'Acco' => $roomRev,
                'fb' => $FB,
                'com' => $Com,
                'lun' => $Lun,
                'other' => $other,
                'total' => $total
            ];


            array_push($returnArr, $Revenue_By_Dates);
        }
        return $returnArr;
    }
    public function Receipt_Voucher_List_Report(Request $request)
    {
        $receipt_voucher_list = Transaction::with('created_by', 'guest.profile', 'room')
            ->whereBetween('hotel_date', [$request->startDate, $request->endDate])
            ->when($request->has('created_by'), function ($query) use ($request) {
                return $query->where('created_by', $request->created_by);
            })
            ->where('trans_type', 'p')
            ->where('recd_type', 'REC')
            ->get();
        return response()->json(['receipt_voucher_list' => $receipt_voucher_list]);
    }

    public function Payment_Voucher_List_Report(Request $request)
    {
        $payment_voucher_list = Transaction::with('created_by', 'guest.profile', 'room')
            ->whereBetween('hotel_date', [$request->startDate, $request->endDate])
            ->when($request->has('created_by'), function ($query) use ($request) {
                return $query->where('created_by', $request->created_by);
            })
            ->where('trans_type', 'p')
            ->where('recd_type', 'PAY')
            ->get();
        return response()->json(['payment_voucher_list' => $payment_voucher_list]);
    }

    public function Rooms_Status_Report(Request $request)
    {
        $room_status = Room::with(['guests.profile', 'room_type', 'roomStatus', 'floors'])
            ->when($request->has('floor_id'), function ($query) use ($request) {
                return $query->where('floor_id', $request->floor_id);
            })
            ->orderBy('floor_id')
            ->orderBy('rm_name_en')
            ->orderBy('rm_name_loc')
            ->get();
        return $room_status;
    }

    public function OO_And_OS_Report(Request $request)
    {
        $OO_AND_OS = OordService::with(['room.room_type'])
            ->where('oord_type', $request->oord_type)
            ->whereDate('start_date', '>=', $request->startDate)
            ->whereDate('start_date', '<=', $request->endDate)
            ->get();
        return response()->json(['OO_AND_OS' => $OO_AND_OS]);
    }

    public function Reservation_Report(Request $request)
    {
        $res_status = [];
        $res_status = $request->res_status;

        $reservation_overview = Guests::with(['profile', 'company', 'reservation_status'])
            ->whereBetween('in_date', [$request->startDate, $request->endDate])
            ->when($request->has('res_status'), function ($query) use ($res_status) {
                return $query->whereIn('res_status', $res_status);
            })->with(['preCharges' => function ($q) {
                $q->selectRaw('sum(amount) as Amount ,guest_id')->groupBy('guest_id');
            }])
            ->get();


        return response()->json(['reservation_overview' => $reservation_overview]);
    }
    public function Res_Amount(Request $request)
    {
        $res_amount = preCharge::where('guest_id', $request->guest_id)
            ->sum('amount');
        return $res_amount;
    }

    public function Income_Statement_Report(Request $request)
    {
        $income_statement = ledgerCat::with(['ledgers' => function ($qu) use ($request) {
            // dd($request->hotel_date);
            $qu->with(['transactions' => function ($que) use ($request) {
                $que->whereDate('hotel_date', $request->hotel_date);
                $que->selectRaw('sum(charge_amount) as charge_amount,ledger_id')->groupBy('ledger_id');
                $que->selectRaw('sum(payment_amount) as payment_amount,ledger_id');
            }]);
        }])
            ->with(['transactionsLedCat' => function ($query) use ($request) {
                $query->whereDate('hotel_date', $request->hotel_date);
                $query->selectRaw('sum(charge_amount) as   charge_amount_by_ledCat,  ledger_cat_id')->groupBy('ledger_cat_id');
                $query->selectRaw('sum(payment_amount) as  payment_amount_by_ledCat, ledger_cat_id');
            }])
            ->get();

        return response()->json(['income_statement' => $income_statement]);
    }

    public function Income_Statement_Report_MTD_YTD(Request $request)
    {
        $income_statement_MTD_YTD = ledgerCat::with(['ledgers' => function ($qu) use ($request) {
            $qu->with(['transactions' => function ($que) use ($request) {

                $que->whereDate('hotel_date', $request->date);
                $que->selectRaw('sum(charge_amount) as charge_amount,ledger_id')->groupBy('ledger_id');
                $que->selectRaw('sum(payment_amount) as payment_amount,ledger_id');
            }])
                ->with(['transactionsMtd' =>  function ($que2) use ($request) {

                    $que2->whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date]);
                    $que2->selectRaw('sum(charge_amount) as charge_amount_MTD,   ledger_id')->groupBy('ledger_id');
                    $que2->selectRaw('sum(payment_amount) as payment_amount_MTD, ledger_id');
                }])
                ->with(['transactionsYtd' => function ($que3) use ($request) {

                    $que3->whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date]);
                    $que3->selectRaw('sum(charge_amount)as   charge_amount_YTD,   ledger_id')->groupBy('ledger_id');
                    $que3->selectRaw('sum(payment_amount) as  payment_amount_YTD, ledger_id');
                }]);
        }])
            ->with(['transactionsLedCat' => function ($query) use ($request) {

                $query->whereDate('hotel_date', $request->date);
                $query->selectRaw('sum(charge_amount) as   charge_amount_by_ledCat,  ledger_cat_id')->groupBy('ledger_cat_id');
                $query->selectRaw('sum(payment_amount) as  payment_amount_by_ledCat, ledger_cat_id');
            }])
            ->with(['transactionsMtdLedCat' =>  function ($query2) use ($request) {

                $query2->whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date]);
                $query2->selectRaw('sum(charge_amount) as  charge_amount_MTD_by_ledCat,    ledger_cat_id')->groupBy('ledger_cat_id');
                $query2->selectRaw('sum(payment_amount) as payment_amount_MTD_by_ledCat,   ledger_cat_id');
            }])
            ->with(['transactionsYtdLedCat' =>  function ($query3) use ($request) {

                $query3->whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date]);
                $query3->selectRaw('sum(charge_amount) as  charge_amount_YTD_by_ledCat,   ledger_cat_id')->groupBy('ledger_cat_id');
                $query3->selectRaw('sum(payment_amount) as payment_amount_YTD_by_ledCat,  ledger_cat_id');
            }])
            ->get();

        return response()->json([

            'income_statement_MTD_YTD'       => $income_statement_MTD_YTD,
        ]);
    }

    public function High_Balance_Report()
    {
        $ret_guest = [];

        $high_balance = Guests::with('profile', 'room')
            ->where('is_checked_in', 1)
            ->where('checked_out', 0)
            ->where('is_reservation', 0)
            ->withSum('transactions', 'charge_amount')
            ->withSum('transactions', 'payment_amount')
            ->get();
        foreach ($high_balance as $oneGuest) {
            $guest_id = ['guest_id' => $oneGuest->id];
            $Balance = $this->generalInterface->guestBalance($guest_id);
            if ($Balance > 0) {
                //  dump($Balance);
                array_push($ret_guest, $oneGuest);
            }
        }

        return response()->json(['Guests-With-Balance' => $ret_guest]);
    }

    public function Occupancy_By_Month(Request $request)
    {
        $Ret_Month = [];
        $year = $request->input('year');

        $startDate = Carbon::createFromFormat('Y-m-d', $year . '-01-01');
        $endDate = Carbon::createFromFormat('Y-m-d', $year . '-12-31');

        for ($MonthDate = $startDate; $MonthDate->lte($endDate); $MonthDate->addMonth()) {

            $Month_Data = $MonthDate->format('F');
            $Occupancy_ByMonth = DayCloseRecord::whereYear('hotel_date', $year)->whereMonth('hotel_date', $MonthDate)
                ->selectRaw('sum(total_room) as Rooms , sum(va_rooms) as VAC ,sum(oc_rooms) as OCC, 
                    (sum(oc_rooms)/sum(total_room))*100  as OCCPercentage,  
                    sum(oo_rooms) as OOrd,  sum(os_rooms) as OInv, 
                    sum(house_use_rooms) as HUse ,sum(comp_rooms) as CMP, 
                    sum(total_rate_room) as Rate,
                    (sum(total_fb)+(sum(total_others))) as OSales,
                    (sum(total_rate_room) /sum(oc_rooms)) as ARR,
             DATE_FORMAT(hotel_date, "%m") as Month')
                ->groupBy('Month')->get();
            $mergedArr = [
                'Month_Data'        => $Month_Data,
                'Occupancy_ByMonth' => $Occupancy_ByMonth,
            ];

            array_push($Ret_Month, $mergedArr);
        }
        return response()->json(['Data_of_Occupancy_ByMonth' => $Ret_Month]);
    }

    public function Occupancy_By_MTD(Request $request)
    {
        $Occupancy_ByMonthTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->hotel_date)->startOfMonth(), $request->hotel_date])
            ->orderBy('hotel_date')->selectRaw('*, (total_rate_room /oc_rooms) as ARR, 
            total_fb + total_others as  OSales,
            (total_room /oc_rooms)*100 as OCCPercentage')
            ->get();

        return response()->json(['Occupancy_ByMonthTD' => $Occupancy_ByMonthTD]);
    }

    public function House_Keeping_By_Status()
    {
        $VADI = [];
        $VACL = [];
        $OO = [];
        $OCDI = [];
        $DO = [];

        $status = Room::selectRaw("CONCAT(fo_status,hk_stauts) as status, rm_name_en,fo_status,hk_stauts")
            ->groupBy('status', 'rm_name_en', 'fo_status', 'hk_stauts')
            ->get();

        foreach ($status as $state) {

            if ($state->status == 'VADI') {
                array_push($VADI, $state->rm_name_en);
            } elseif ($state->status == 'VACL') {
                array_push($VACL, $state->rm_name_en);
            } elseif ($state->fo_status == 'OO') {
                array_push($OO, $state->rm_name_en);
            } elseif ($state->status == 'OCDI') {
                array_push($OCDI, $state->rm_name_en);
            } elseif ($state->fo_status == 'DO') {
                array_push($DO, $state->rm_name_en);
            }
        }
        return response()->json(['House_Keeping_By_Status' => [
            'VADI' => $VADI,
            'VACL' => $VACL,
            'OO' => $OO,
            'OCDI' => $OCDI,
            'DO' => $DO,
        ]]);
    }

    public function Daily_Sales_Report(Request $request)
    {
        $sales_arr = [];
        $startDate = $request->startDate;
        $endDate = $request->endDate;


        $daily_sales = DayCloseRecord::whereBetween('hotel_date', [$startDate, $endDate])
            ->orderBy('hotel_date', 'asc')
            ->get();

        foreach ($daily_sales as $daily_sale) {

            $request = ['startDate' => $daily_sale->hotel_date, 'endDate' => $daily_sale->hotel_date];

            $Sales = $this->Revenue_By_Dates_by_ledgerCat($request);

            $data = $daily_sale->whereDate('hotel_date', Carbon::parse($daily_sale->hotel_date)->startOfDay())
                ->selectRaw('hotel_date,act_chkin_rooms,act_chkout_rooms,guest_inhouse_pax,oc_rooms,
           (total_rate_room /oc_rooms)*100 as occupancyPer')
                ->orderBy('hotel_date', 'asc')
                ->get()
                ->map(function ($q) use ($Sales) {
                    $q->sale = $Sales;
                    return $q;
                });

            array_push($sales_arr, $data);
        }


        return response()->json(['daily_sales' => $sales_arr]);
    }

    public function Reservation_By_Company(Request $request)
    {
        $reservation_by_company = Guests::with(['profile', 'company', 'createdBy'])
            ->whereDate('in_date', '>=', $request->startDate)
            ->whereDate('out_date', '<=', $request->endDate)
            ->where('company_id', $request->company_id)
            ->withSum('transactions', 'charge_amount')
            ->get();
        return response()->json(['reservation_by_company' => $reservation_by_company]);
    }


    public function Voucher_List(Request $request)
    {
        $voucher_list = Transaction::with(['room', 'guest.profile'])->whereBetween('hotel_date', [$request->startDate, $request->endDate])
            ->orderBy('hotel_date', 'asc')
            ->when($request->has('guest_id'), function ($qu) use ($request) {
                return  $qu->where('guest_id', $request->guest_id);
            })
            ->when($request->trans_no != null, function ($que) use ($request) {
                return  $que->where('trans_no', $request->trans_no);
            })
            ->where('trans_type', 'P')->where('recd_type', $request->recd_type)
            ->get();

        return response()->json(['voucher_list' => $voucher_list]);
    }

    public function Transactions_List_View(Request $request)
    {
        $arr = [];
        $transaction_list_view = Transaction::with('room', 'ledger', 'guest')
            ->where(function ($q) use ($request) {
                if ($request->input('startDate') != 'null') {

                    $q->whereDate('hotel_date', '>=', $request->input('startDate'))
                        ->whereDate('hotel_date', '<=', $request->input('endDate'));
                }

                if ($request->has('folio_no')) {
                    $q->where('guest_id', 'LIKE', '%' . $request->input('folio_no') . '%');
                }

                if ($request->has('voucher_no')) {
                    $q->where('trans_no', 'LIKE', '%' . $request->input('voucher_no') . '%');
                }

                if ($request->has('user_id')) {
                    $q->where('created_by', 'LIKE', '%' . $request->input('user_id') . '%');
                }

                if ($request->has('ledger_id')) {
                    $ledger_id = [];
                    $ledger_id = $request->input('ledger_id');
                    $q->whereIn('ledger_id', $ledger_id);
                }

                if ($request->has('payment_type_id')) {
                    $payment_type_id = [];
                    $payment_type_id = $request->input('payment_type_id');
                    $q->whereIn('payment_type_id', $payment_type_id);
                }

                if ($request->has('room_no')) {
                    $q->whereHas('room', function ($qu) use ($request) {
                        $qu->where('rm_name_en', 'LIKE', '%' . $request->input('room_no') . '%');
                        $qu->orWhere('rm_name_loc', 'LIKE', '%' . $request->input('room_no') . '%');
                    });
                }
            })->get();

        $total_charge_amount = Transaction::where(function ($q) use ($request) {
            if ($request->input('startDate') != 'null') {

                $q->whereDate('hotel_date', '>=', $request->input('startDate'))
                    ->whereDate('hotel_date', '<=', $request->input('endDate'));
            }
            if ($request->has('folio_no')) {
                $q->where('guest_id', 'LIKE', '%' . $request->input('folio_no') . '%');
            }

            if ($request->has('voucher_no')) {
                $q->where('trans_no', 'LIKE', '%' . $request->input('voucher_no') . '%');
            }

            if ($request->has('user_id')) {
                $q->where('created_by', 'LIKE', '%' . $request->input('user_id') . '%');
            }

            if ($request->has('ledger_id')) {
                $ledger_id = [];
                $ledger_id = $request->input('ledger_id');
                $q->whereIn('ledger_id', $ledger_id);
            }

            if ($request->has('payment_type_id')) {
                $payment_type_id = [];
                $payment_type_id = $request->input('payment_type_id');
                $q->whereIn('payment_type_id', $payment_type_id);
            }

            if ($request->has('room_no')) {
                $q->whereHas('room', function ($qu) use ($request) {
                    $qu->where('rm_name_en', 'LIKE', '%' . $request->input('room_no') . '%');
                    $qu->orWhere('rm_name_loc', 'LIKE', '%' . $request->input('room_no') . '%');
                });
            }
        })->sum('charge_amount');

        $total_payment_amount = Transaction::where(function ($q) use ($request) {
            if ($request->input('startDate') != 'null') {

                $q->whereDate('hotel_date', '>=', $request->input('startDate'))
                    ->whereDate('hotel_date', '<=', $request->input('endDate'));
            }
            if ($request->has('folio_no')) {
                $q->where('guest_id', 'LIKE', '%' . $request->input('folio_no') . '%');
            }

            if ($request->has('voucher_no')) {
                $q->where('trans_no', 'LIKE', '%' . $request->input('voucher_no') . '%');
            }

            if ($request->has('user_id')) {
                $q->where('created_by', 'LIKE', '%' . $request->input('user_id') . '%');
            }

            if ($request->has('ledger_id')) {
                $ledger_id = [];
                $ledger_id = $request->input('ledger_id');
                $q->whereIn('ledger_id', $ledger_id);
            }

            if ($request->has('payment_type_id')) {
                $payment_type_id = [];
                $payment_type_id = $request->input('payment_type_id');
                $q->whereIn('payment_type_id', $payment_type_id);
            }

            if ($request->has('room_no')) {
                $q->whereHas('room', function ($qu) use ($request) {
                    $qu->where('rm_name_en', 'LIKE', '%' . $request->input('room_no') . '%');
                    $qu->orWhere('rm_name_loc', 'LIKE', '%' . $request->input('room_no') . '%');
                });
            }
        })->sum('payment_amount');

        array_push($arr, $transaction_list_view);
        array_push($arr, $total_charge_amount);
        array_push($arr, $total_payment_amount);

        return response()->json([
            'All_data' => [

                'Transaction_list_view'   => $transaction_list_view,
                'Total_charges'           => $total_charge_amount,
                'Total_payments'          => $total_payment_amount
            ]
        ]);
    }

    public function Manager1_Report(Request $request)
    {

        $Total_Rooms_InHotel = [];

        $manager_report = ledgerCat::with(['ledgers' => function ($qu) use ($request) {
            $qu->with(['transactions' => function ($que) use ($request) {

                $que->whereDate('hotel_date', $request->date);
                $que->selectRaw('sum(charge_amount) as charge_amount,ledger_id')->groupBy('ledger_id');
                $que->selectRaw('sum(payment_amount) as payment_amount,ledger_id');
            }])
                ->with(['transactionsMtd' =>  function ($que2) use ($request) {

                    $que2->whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date]);
                    $que2->selectRaw('sum(charge_amount) as charge_amount_MTD,   ledger_id')->groupBy('ledger_id');
                    $que2->selectRaw('sum(payment_amount) as payment_amount_MTD, ledger_id');
                }])
                ->with(['transactionsYtd' => function ($que3) use ($request) {

                    $que3->whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date]);
                    $que3->selectRaw('sum(charge_amount)as   charge_amount_YTD,   ledger_id')->groupBy('ledger_id');
                    $que3->selectRaw('sum(payment_amount) as  payment_amount_YTD, ledger_id');
                }]);
        }])
            ->with(['transactionsLedCat' => function ($query) use ($request) {

                $query->whereDate('hotel_date', $request->date);
                $query->selectRaw('sum(charge_amount) as   charge_amount_by_ledCat,  ledger_cat_id')->groupBy('ledger_cat_id');
                $query->selectRaw('sum(payment_amount) as  payment_amount_by_ledCat, ledger_cat_id');
            }])
            ->with(['transactionsMtdLedCat' =>  function ($query2) use ($request) {

                $query2->whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date]);
                $query2->selectRaw('sum(charge_amount) as  charge_amount_MTD_by_ledCat,    ledger_cat_id')->groupBy('ledger_cat_id');
                $query2->selectRaw('sum(payment_amount) as payment_amount_MTD_by_ledCat,   ledger_cat_id');
            }])
            ->with(['transactionsYtdLedCat' =>  function ($query3) use ($request) {

                $query3->whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date]);
                $query3->selectRaw('sum(charge_amount) as  charge_amount_YTD_by_ledCat,   ledger_cat_id')->groupBy('ledger_cat_id');
                $query3->selectRaw('sum(payment_amount) as payment_amount_YTD_by_ledCat,  ledger_cat_id');
            }])
            ->get();


        $guestHouse = DayCloseRecord::whereDate('hotel_date', $request->date)->selectRaw('sum(guest_inhouse_pax) as guestHouse')->get();

        // return $guestHouse;
        $guestHouseMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(guest_inhouse_pax) as guestHouseMTD')->get();

        $guestHouseYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(guest_inhouse_pax) as guestHouseYTD')->get();

        $guestHouseLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(guest_inhouse_pax) as guestHouseLYSD')->get();


        $guestHouseLYY = DayCloseRecord::selectRaw('sum(guest_inhouse_pax)as guestHouseLYY')
            ->whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->get();

        $guestHouseLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(guest_inhouse_pax) as guestHouseLYMTD')->get();

        //  dd($guestHouseMTD[0]->Guests_InHouseMTD);
        $Total_Rooms_InHotel['guest_House'] = $guestHouse->map(function ($q) use ($guestHouseMTD, $guestHouse, $guestHouseYTD, $guestHouseLYSD, $guestHouseLYY, $guestHouseLYMTD) {
            $q->guestHouseMTD = $guestHouseMTD[0]->guestHouseMTD;
            $q->guestHouseYTD = $guestHouseYTD[0]->guestHouseYTD;
            $q->guestHouseLYSD = $guestHouseLYSD[0]->guestHouseLYSD;
            $q->guestHouseLYY = $guestHouseLYY[0]->guestHouseLYY;
            $q->guestHouseLYMTD = $guestHouseLYMTD[0]->guestHouseLYMTD;
            return $q;
        });


        $RoomsOccupied = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(oc_rooms)as RoomsOccupied')->get();

        $RoomsOccupiedMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(oc_rooms) as RoomsOccupiedMTD')->get();

        $RoomsOccupiedYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(oc_rooms) as RoomsOccupiedYTD')->get();

        $RoomsOccupiedLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(oc_rooms) as RoomsOccupiedLYSD')->get();

        $RoomsOccupiedLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(oc_rooms) as RoomsOccupiedLYY')
            ->get();
        $RoomsOccupiedLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(oc_rooms) as RoomsOccupiedLYMTD')->get();

        $Total_Rooms_InHotel['RoomsOccupied'] = $RoomsOccupied->map(function ($q) use ($RoomsOccupiedMTD, $RoomsOccupied, $RoomsOccupiedYTD, $RoomsOccupiedLYSD, $RoomsOccupiedLYY, $RoomsOccupiedLYMTD) {
            $q->RoomsOccupiedMTD = $RoomsOccupiedMTD[0]->RoomsOccupiedMTD;
            $q->RoomsOccupiedYTD = $RoomsOccupiedYTD[0]->RoomsOccupiedYTD;
            $q->RoomsOccupiedLYSD = $RoomsOccupiedLYSD[0]->RoomsOccupiedLYSD;
            $q->RoomsOccupiedLYY = $RoomsOccupiedLYY[0]->RoomsOccupiedLYY;
            $q->RoomsOccupiedLYMTD = $RoomsOccupiedLYMTD[0]->RoomsOccupiedLYMTD;
            return $q;
        });



        $TotalRooms = DayCloseRecord::whereDate('hotel_date', $request->date)->selectRaw('sum(total_room) as TotalRooms')->get();

        $TotalRoomsMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(total_room) as TotalRoomsMTD')->get();

        $TotalRoomsYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(total_room) as TotalRoomsYTD')->get();
        $TotalRoomsLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(total_room) as TotalRoomsLYSD')->get();

        $TotalRoomsLYY = DayCloseRecord::selectRaw('sum(total_room) as TotalRoomsLYY')
            ->whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->get();

        $TotalRoomLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(total_room) as TotalRoomLYMTD')->get();

        $Total_Rooms_InHotel['total_rooms'] = $TotalRooms->map(function ($q) use ($TotalRoomsMTD, $TotalRooms, $TotalRoomsYTD, $TotalRoomsLYSD, $TotalRoomsLYY, $TotalRoomLYMTD) {
            $q->TotalRoomsMTD = $TotalRoomsMTD[0]->TotalRoomsMTD;
            $q->TotalRoomsYTD = $TotalRoomsYTD[0]->TotalRoomsYTD;
            $q->TotalRoomsLYSD = $TotalRoomsLYSD[0]->TotalRoomsLYSD;
            $q->TotalRoomsLYY = $TotalRoomsLYY[0]->TotalRoomsLYY;
            $q->TotalRoomLYMTD = $TotalRoomLYMTD[0]->TotalRoomLYMTD;
            return $q;
        });


        $AvailableRooms = DayCloseRecord::whereDate('hotel_date', $request->date)->selectRaw('sum(total_room-oc_rooms) as AvailableRooms')->get();

        $AvailableRoomsMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(total_room-oc_rooms) as AvailableRoomsMTD')->get();

        $AvailableRoomsYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(total_room-oc_rooms) as AvailableRoomsYTD')->get();

        $AvailableRoomsLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(total_room-oc_rooms) as AvailableRoomsLYSD')->get();


        $AvailableRoomsLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(total_room-oc_rooms) as AvailableRoomsLYY')->get();

        $AvailableRoomsLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(total_room-oc_rooms) as AvailableRoomsLYMTD')->get();

        $Total_Rooms_InHotel['AvailableRooms'] = $AvailableRooms->map(function ($q) use ($AvailableRoomsMTD, $AvailableRooms, $AvailableRoomsYTD, $AvailableRoomsLYSD, $AvailableRoomsLYY, $AvailableRoomsLYMTD) {
            $q->AvailableRoomsMTD = $AvailableRoomsMTD[0]->AvailableRoomsMTD;
            $q->AvailableRoomsYTD = $AvailableRoomsYTD[0]->AvailableRoomsYTD;
            $q->AvailableRoomsLYSD = $AvailableRoomsLYSD[0]->AvailableRoomsLYSD;
            $q->AvailableRoomsLYY = $AvailableRoomsLYY[0]->AvailableRoomsLYY;
            $q->AvailableRoomsLYMTD = $AvailableRoomsLYMTD[0]->AvailableRoomsLYMTD;
            return $q;
        });



        $RoomsOccupiedPercent = DayCloseRecord::whereDate('hotel_date', $request->date)->selectRaw('sum((oc_rooms/total_room)*100) as  RoomsOccupiedPercent')->get();

        $RoomsOccupiedPercentMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum((oc_rooms/total_room)*100) as  RoomsOccupiedPercentMTD')->get();

        $RoomsOccupiedPercentYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum((oc_rooms/total_room)*100) as  RoomsOccupiedPercentYTD')->get();

        $RoomsOccupiedPercentLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum((oc_rooms/total_room)*100) as  RoomsOccupiedPercentLYSD')->get();


        $RoomsOccupiedPercentLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum((oc_rooms/total_room)*100) as  RoomsOccupiedPercentLYY')->get();

        $RoomsOccupiedPercentLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum((oc_rooms/total_room)*100) as RoomsOccupiedPercentLYMTD')->get();

        $Total_Rooms_InHotel['RoomsOccupiedPercent'] = $RoomsOccupiedPercent->map(function ($q) use ($RoomsOccupiedPercentMTD, $RoomsOccupiedPercent, $RoomsOccupiedPercentYTD, $RoomsOccupiedPercentLYY, $RoomsOccupiedPercentLYSD, $RoomsOccupiedPercentLYMTD) {
            $q->RoomsOccupiedPercentMTD = $RoomsOccupiedPercentMTD[0]->RoomsOccupiedPercentMTD;
            $q->RoomsOccupiedPercentYTD = $RoomsOccupiedPercentYTD[0]->RoomsOccupiedPercentYTD;
            $q->RoomsOccupiedPercentLYY = $RoomsOccupiedPercentLYY[0]->RoomsOccupiedPercentLYY;
            $q->RoomsOccupiedPercentLYSD = $RoomsOccupiedPercentLYSD[0]->RoomsOccupiedPercentLYSD;
            $q->RoomsOccupiedPercentLYMTD = $RoomsOccupiedPercentLYMTD[0]->RoomsOccupiedPercentLYMTD;
            return $q;
        });


        $ComplimentaryRooms = DayCloseRecord::whereDate('hotel_date', $request->date)->selectRaw('sum(comp_rooms) as  ComplimentaryRooms')->get();

        $ComplimentaryRoomsMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(comp_rooms) as  ComplimentaryRoomsMTD')->get();

        $ComplimentaryRoomsYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(comp_rooms) as  ComplimentaryRoomsYTD')->get();

        $ComplimentaryRoomsLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(comp_rooms) as  ComplimentaryRoomsLYSD')->get();


        $ComplimentaryRoomsLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(comp_rooms) as  ComplimentaryRoomsLYY')->get();
        $ComplimentaryRoomsLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(comp_rooms) as ComplimentaryRoomsLYMTD')->get();

        $Total_Rooms_InHotel['ComplimentaryRooms'] = $ComplimentaryRooms->map(function ($q) use ($ComplimentaryRoomsMTD, $ComplimentaryRooms, $ComplimentaryRoomsYTD, $ComplimentaryRoomsLYSD, $ComplimentaryRoomsLYY, $ComplimentaryRoomsLYMTD) {
            $q->ComplimentaryRoomsMTD = $ComplimentaryRoomsMTD[0]->ComplimentaryRoomsMTD;
            $q->ComplimentaryRoomsYTD = $ComplimentaryRoomsYTD[0]->ComplimentaryRoomsYTD;
            $q->ComplimentaryRoomsLYSD = $ComplimentaryRoomsLYSD[0]->ComplimentaryRoomsLYSD;
            $q->ComplimentaryRoomsLYY = $ComplimentaryRoomsLYY[0]->ComplimentaryRoomsLYY;
            $q->ComplimentaryRoomsLYMTD = $ComplimentaryRoomsLYMTD[0]->ComplimentaryRoomsLYMTD;
            return $q;
        });



        $HouseUseRooms = DayCloseRecord::whereDate('hotel_date', $request->date)->selectRaw('sum(house_use_rooms) as  HouseUseRooms')->get();

        $HouseUseRoomsMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(house_use_rooms) as  HouseUseRoomsMTD')->get();

        $HouseUseRoomsYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('hotel_date,(house_use_rooms) as  HouseUseRoomsYTD')->get();

        $HouseUseRoomsLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(house_use_rooms) as  HouseUseRoomsLYSD')->get();

        $HouseUseRoomsLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(house_use_rooms) as  HouseUseRoomsLYY')->get();

        $HouseUseRoomsLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(house_use_rooms) as  HouseUseRoomsLYMTD')->get();

        $Total_Rooms_InHotel['HouseUseRooms'] = $HouseUseRooms->map(function ($q) use ($HouseUseRoomsMTD, $HouseUseRooms, $HouseUseRoomsYTD, $HouseUseRoomsLYSD, $HouseUseRoomsLYY, $HouseUseRoomsLYMTD) {
            $q->HouseUseRoomsMTD = $HouseUseRoomsMTD[0]->HouseUseRoomsMTD;
            $q->HouseUseRoomsYTD = $HouseUseRoomsYTD[0]->HouseUseRoomsYTD;
            $q->HouseUseRoomsLYSD = $HouseUseRoomsLYSD[0]->HouseUseRoomsLYSD;
            $q->ComplimentaryRoomsLYY = $HouseUseRoomsLYY[0]->HouseUseRoomsLYY;
            $q->HouseUseRoomsLYMTD = $HouseUseRoomsLYMTD[0]->HouseUseRoomsLYMTD;
            return $q;
        });


        $OutOfOrderRooms = DayCloseRecord::whereDate('hotel_date', $request->date)->selectRaw('sum(oo_rooms) as  OutOfOrderRooms')->get();

        $OutOfOrderRoomsMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(oo_rooms) as  OutOfOrderRoomsMTD')->get();

        $OutOfOrderRoomsYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(oo_rooms) as  OutOfOrderRoomsYTD')->get();

        $OutOfOrderRoomsLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(oo_rooms) as  OutOfOrderRoomsLYSD')->get();

        $OutOfOrderRoomsLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(oo_rooms) as  OutOfOrderRoomsLYY')->get();

        $OutOfOrderRoomsLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(oo_rooms) as  OutOfOrderRoomsLYMTD')->get();

        $Total_Rooms_InHotel['OutOfOrderRooms'] = $OutOfOrderRooms->map(function ($q) use ($OutOfOrderRoomsMTD, $OutOfOrderRooms, $OutOfOrderRoomsYTD, $OutOfOrderRoomsLYSD, $OutOfOrderRoomsLYY, $OutOfOrderRoomsLYMTD) {
            $q->OutOfOrderRoomsMTD = $OutOfOrderRoomsMTD[0]->OutOfOrderRoomsMTD;
            $q->OutOfOrderRoomsYTD = $OutOfOrderRoomsYTD[0]->OutOfOrderRoomsYTD;
            $q->OutOfOrderRoomsLYSD = $OutOfOrderRoomsLYSD[0]->OutOfOrderRoomsLYSD;
            $q->OutOfOrderRoomsLYY = $OutOfOrderRoomsLYY[0]->OutOfOrderRoomsLYY;
            $q->OutOfOrderRoomsLYMTD = $OutOfOrderRoomsLYMTD[0]->OutOfOrderRoomsLYMTD;
            return $q;
        });

        $TotalRoom_minusOOORooms = DayCloseRecord::whereDate('hotel_date', $request->date)->selectRaw('sum(total_room-oo_rooms) as  TotalRoom_minusOOORooms')->get();

        $TotalRoom_minus_OOORoomsMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(total_room-oo_rooms) as  TotalRoom_minus_OOORoomsMTD')->get();

        $TotalRoom_minus_OOORoomsYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(total_room-oo_rooms) as  TotalRoom_minus_OOORoomsYTD')->get();

        $TotalRoom_minus_OOORoomsLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())

            ->selectRaw('sum(total_room-oo_rooms) as  TotalRoom_minus_OOORoomsLYSD')->get();

        $TotalRoom_minus_OOORoomsLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(total_room-oo_rooms) as  TotalRoom_minus_OOORoomsLYY')->get();

        $TotalRoom_minus_OOORoomsLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(total_room-oo_rooms) as  TotalRoom_minus_OOORoomsLYMTD')->get();

        $Total_Rooms_InHotel['TotalRoom_minusOOORooms'] = $TotalRoom_minusOOORooms->map(function ($q) use ($TotalRoom_minus_OOORoomsMTD, $TotalRoom_minusOOORooms, $TotalRoom_minus_OOORoomsYTD, $TotalRoom_minus_OOORoomsLYSD, $TotalRoom_minus_OOORoomsLYY, $TotalRoom_minus_OOORoomsLYMTD) {
            $q->TotalRoom_minus_OOORoomsMTD = $TotalRoom_minus_OOORoomsMTD[0]->TotalRoom_minus_OOORoomsMTD;
            $q->TotalRoom_minus_OOORoomsYTD = $TotalRoom_minus_OOORoomsYTD[0]->TotalRoom_minus_OOORoomsYTD;
            $q->TotalRoom_minus_OOORoomsLYSD = $TotalRoom_minus_OOORoomsLYSD[0]->TotalRoom_minus_OOORoomsLYSD;
            $q->TotalRoom_minus_OOORoomsLYY = $TotalRoom_minus_OOORoomsLYY[0]->TotalRoom_minus_OOORoomsLYY;
            $q->TotalRoom_minus_OOORoomsLYMTD = $TotalRoom_minus_OOORoomsLYMTD[0]->TotalRoom_minus_OOORoomsLYMTD;
            return $q;
        });




        $Available_Room_minusOOORooms = DayCloseRecord::whereDate('hotel_date', $request->date)->selectRaw('sum((total_room-oc_rooms)-oo_rooms) as  Available_Room_minusOOORooms')->get();

        $Available_Room_minusOOORoomsMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum((total_room-oc_rooms)-oo_rooms) as  Available_Room_minusOOORoomsMTD')->get();

        $Available_Room_minusOOORoomsYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('hotel_date,((total_room-oc_rooms)-oo_rooms) as  Available_Room_minusOOORoomsYTD')->get();

        $Available_Room_minusOOORoomsLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())

            ->selectRaw('sum((total_room-oc_rooms)-oo_rooms) as  Available_Room_minusOOORoomsLYSD')->get();

        $Available_Room_minusOOORoomsLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum((total_room-oc_rooms)-oo_rooms ) as  Available_Room_minusOOORoomsLYY')->get();

        $Available_Room_minusOOORoomsLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum((total_room-oc_rooms)-oo_rooms) as  Available_Room_minusOOORoomsLYMTD')->get();

        $Total_Rooms_InHotel['Available_Room_minusOOORooms'] = $Available_Room_minusOOORooms->map(function ($q) use ($Available_Room_minusOOORoomsMTD, $Available_Room_minusOOORooms, $Available_Room_minusOOORoomsYTD, $Available_Room_minusOOORoomsLYSD, $Available_Room_minusOOORoomsLYY, $Available_Room_minusOOORoomsLYMTD) {
            $q->Available_Room_minusOOORoomsMTD = $Available_Room_minusOOORoomsMTD[0]->Available_Room_minusOOORoomsMTD;
            $q->Available_Room_minusOOORoomsYTD = $Available_Room_minusOOORoomsYTD[0]->Available_Room_minusOOORoomsYTD;
            $q->Available_Room_minusOOORoomsLYSD = $Available_Room_minusOOORoomsLYSD[0]->Available_Room_minusOOORoomsLYSD;
            $q->Available_Room_minusOOORoomsLYY = $Available_Room_minusOOORoomsLYY[0]->Available_Room_minusOOORoomsLYY;
            $q->Available_Room_minusOOORoomsLYMTD = $Available_Room_minusOOORoomsLYMTD[0]->Available_Room_minusOOORoomsLYMTD;
            return $q;
        });



        $RoomsOccupiedPercent_minus_OOORooms = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum((oc_rooms/total_room)*100 -(oo_rooms)) as RoomsOccupiedPercent_minus_OOORooms')->get();


        $RoomsOccupiedPercent_minus_OOORoomsMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum((oc_rooms/total_room)*100 -(oo_rooms))  as  RoomsOccupiedPercent_minus_OOORoomsMTD')->get();

        $RoomsOccupiedPercent_minus_OOORoomsYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum((oc_rooms/total_room)*100 -(oo_rooms))  as  RoomsOccupiedPercent_minus_OOORoomsYTD')->get();

        $RoomsOccupiedPercent_minus_OOORoomsLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum((oc_rooms/total_room)*100 -(oo_rooms)) as  RoomsOccupiedPercent_minus_OOORoomsLYSD')->get();

        $RoomsOccupiedPercent_minus_OOORoomsLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum((oc_rooms/total_room)*100 -(oo_rooms)) as  RoomsOccupiedPercent_minus_OOORoomsLYY')->get();

        $RoomsOccupiedPercent_minus_OOORoomsLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum((oc_rooms/total_room)*100 -(oo_rooms)) as  RoomsOccupiedPercent_minus_OOORoomsLYMTD')->get();


        $Total_Rooms_InHotel['RoomsOccupiedPercent_minus_OOORooms'] = $RoomsOccupiedPercent_minus_OOORooms->map(function ($q) use ($RoomsOccupiedPercent_minus_OOORoomsMTD, $RoomsOccupiedPercent_minus_OOORooms, $RoomsOccupiedPercent_minus_OOORoomsYTD, $RoomsOccupiedPercent_minus_OOORoomsLYSD, $RoomsOccupiedPercent_minus_OOORoomsLYY, $RoomsOccupiedPercent_minus_OOORoomsLYMTD) {
            $q->RoomsOccupiedPercent_minus_OOORoomsMTD = $RoomsOccupiedPercent_minus_OOORoomsMTD[0]->RoomsOccupiedPercent_minus_OOORoomsMTD;
            $q->RoomsOccupiedPercent_minus_OOORoomsYTD = $RoomsOccupiedPercent_minus_OOORoomsYTD[0]->RoomsOccupiedPercent_minus_OOORoomsYTD;
            $q->RoomsOccupiedPercent_minus_OOORoomsLYSD = $RoomsOccupiedPercent_minus_OOORoomsLYSD[0]->RoomsOccupiedPercent_minus_OOORoomsLYSD;
            $q->RoomsOccupiedPercent_minus_OOORoomsLYY = $RoomsOccupiedPercent_minus_OOORoomsLYY[0]->RoomsOccupiedPercent_minus_OOORoomsLYY;
            $q->RoomsOccupiedPercent_minus_OOORoomsLYMTD = $RoomsOccupiedPercent_minus_OOORoomsLYMTD[0]->RoomsOccupiedPercent_minus_OOORoomsLYMTD;
            return $q;
        });

        $RoomsOccupied_minus_CompsHouse_Rooms = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(oc_rooms-(comp_rooms/house_use_rooms)) as RoomsOccupied_minus_CompsHouse_Rooms')->get();

        $RoomsOccupied_minus_CompsHouse_RoomsMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(oc_rooms-(comp_rooms/house_use_rooms)) as  RoomsOccupied_minus_CompsHouse_RoomsMTD')->get();

        $RoomsOccupied_minus_CompsHouse_RoomsYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('hotel_date,(oc_rooms-(comp_rooms/house_use_rooms))  as RoomsOccupied_minus_CompsHouse_RoomsYTD')->get();

        $RoomsOccupied_minus_CompsHouse_RoomsLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(oc_rooms-(comp_rooms/house_use_rooms)) as  RoomsOccupied_minus_CompsHouse_RoomsLYSD')->get();

        $RoomsOccupied_minus_CompsHouse_RoomsLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(oc_rooms-(comp_rooms/house_use_rooms)) as  RoomsOccupied_minus_CompsHouse_RoomsLYY')->get();

        $RoomsOccupied_minus_CompsHouse_RoomsLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(oc_rooms-(comp_rooms/house_use_rooms)) as  RoomsOccupied_minus_CompsHouse_RoomsLYMTD')->get();

        $Total_Rooms_InHotel['RoomsOccupied_minus_CompsHouse_Rooms'] = $RoomsOccupied_minus_CompsHouse_Rooms->map(function ($q) use ($RoomsOccupied_minus_CompsHouse_RoomsMTD, $RoomsOccupied_minus_CompsHouse_Rooms, $RoomsOccupied_minus_CompsHouse_RoomsYTD, $RoomsOccupied_minus_CompsHouse_RoomsLYSD, $RoomsOccupied_minus_CompsHouse_RoomsLYY, $RoomsOccupied_minus_CompsHouse_RoomsLYMTD) {
            $q->RoomsOccupied_minus_CompsHouse_RoomsMTD = $RoomsOccupied_minus_CompsHouse_RoomsMTD[0]->RoomsOccupied_minus_CompsHouse_RoomsMTD;
            $q->RoomsOccupied_minus_CompsHouse_RoomsYTD = $RoomsOccupied_minus_CompsHouse_RoomsYTD[0]->RoomsOccupied_minus_CompsHouse_RoomsYTD;
            $q->RoomsOccupied_minus_CompsHouse_RoomsLYSD = $RoomsOccupied_minus_CompsHouse_RoomsLYSD[0]->RoomsOccupied_minus_CompsHouse_RoomsLYSD;
            $q->RoomsOccupied_minus_CompsHouse_RoomsLYY = $RoomsOccupied_minus_CompsHouse_RoomsLYY[0]->RoomsOccupied_minus_CompsHouse_RoomsLYY;
            $q->RoomsOccupied_minus_CompsHouse_RoomsLYMTD = $RoomsOccupied_minus_CompsHouse_RoomsLYMTD[0]->RoomsOccupied_minus_CompsHouse_RoomsLYMTD;
            return $q;
        });

        $OccRooms_inc_Comps_minus_House_Rooms = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum((oc_rooms+comp_rooms)-house_use_rooms) as OccRooms_inc_Comps_minus_House_Rooms')->get();

        $OccRooms_inc_Comps_minus_House_RoomsMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(oc_rooms-(comp_rooms/house_use_rooms)) as  OccRooms_inc_Comps_minus_House_RoomsMTD')->get();

        $OccRooms_inc_Comps_minus_House_RoomsYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(oc_rooms-(comp_rooms/house_use_rooms))  as OccRooms_inc_Comps_minus_House_RoomsYTD')->get();

        $OccRooms_inc_Comps_minus_House_RoomsLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(oc_rooms-(comp_rooms/house_use_rooms)) as  OccRooms_inc_Comps_minus_House_RoomsLYSD')->get();

        $OccRooms_inc_Comps_minus_House_RoomsLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(oc_rooms-(comp_rooms/house_use_rooms)) as  OccRooms_inc_Comps_minus_House_RoomsLYY')->get();

        $OccRooms_inc_Comps_minus_House_RoomsLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(oc_rooms-(comp_rooms/house_use_rooms)) as  OccRooms_inc_Comps_minus_House_RoomsLYMTD')->get();

        $Total_Rooms_InHotel['OccRooms_inc_Comps_minus_House_Rooms'] = $OccRooms_inc_Comps_minus_House_Rooms->map(function ($q) use ($OccRooms_inc_Comps_minus_House_RoomsMTD, $OccRooms_inc_Comps_minus_House_Rooms, $OccRooms_inc_Comps_minus_House_RoomsYTD, $OccRooms_inc_Comps_minus_House_RoomsLYSD, $OccRooms_inc_Comps_minus_House_RoomsLYY, $OccRooms_inc_Comps_minus_House_RoomsLYMTD) {
            $q->OccRooms_inc_Comps_minus_House_RoomsMTD = $OccRooms_inc_Comps_minus_House_RoomsMTD[0]->OccRooms_inc_Comps_minus_House_RoomsMTD;
            $q->OccRooms_inc_Comps_minus_House_RoomsYTD = $OccRooms_inc_Comps_minus_House_RoomsYTD[0]->OccRooms_inc_Comps_minus_House_RoomsYTD;
            $q->OccRooms_inc_Comps_minus_House_RoomsLYSD = $OccRooms_inc_Comps_minus_House_RoomsLYSD[0]->OccRooms_inc_Comps_minus_House_RoomsLYSD;
            $q->OccRooms_inc_Comps_minus_House_RoomsLYY = $OccRooms_inc_Comps_minus_House_RoomsLYY[0]->OccRooms_inc_Comps_minus_House_RoomsLYY;
            $q->OccRooms_inc_Comps_minus_House_RoomsLYMTD = $OccRooms_inc_Comps_minus_House_RoomsLYMTD[0]->OccRooms_inc_Comps_minus_House_RoomsLYMTD;
            return $q;
        });



        $OccRoomsPercent_inc_Comps_minus_House_Rooms = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum((oc_rooms+comp_rooms)-house_use_rooms)*100 as OccRoomsPercent_inc_Comps_minus_House_Rooms')->get();

        $OccRoomsPercent_inc_Comps_minus_House_RoomsMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum((oc_rooms+comp_rooms)-house_use_rooms)*100 as  OccRoomsPercent_inc_Comps_minus_House_RoomsMTD')->get();

        $OccRoomsPercent_inc_Comps_minus_House_RoomsYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum((oc_rooms+comp_rooms)-house_use_rooms)*100  as OccRoomsPercent_inc_Comps_minus_House_RoomsYTD')->get();

        $OccRoomsPercent_inc_Comps_minus_House_RoomsLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum((oc_rooms+comp_rooms)-house_use_rooms)*100 as  OccRoomsPercent_inc_Comps_minus_House_RoomsLYSD')->get();

        $OccRoomsPercent_inc_Comps_minus_House_RoomsLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum((oc_rooms+comp_rooms)-house_use_rooms)*100 as  OccRoomsPercent_inc_Comps_minus_House_RoomsLYY')->get();

        $OccRoomsPercent_inc_Comps_minus_House_RoomsLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum((oc_rooms+comp_rooms)-house_use_rooms)*100 as  OccRoomsPercent_inc_Comps_minus_House_RoomsLYMTD')->get();

        $Total_Rooms_InHotel['OccRoomsPercent_inc_Comps_minus_House_Rooms'] = $OccRoomsPercent_inc_Comps_minus_House_Rooms->map(function ($q) use ($OccRoomsPercent_inc_Comps_minus_House_RoomsMTD, $OccRoomsPercent_inc_Comps_minus_House_Rooms, $OccRoomsPercent_inc_Comps_minus_House_RoomsYTD, $OccRoomsPercent_inc_Comps_minus_House_RoomsLYSD, $OccRoomsPercent_inc_Comps_minus_House_RoomsLYY, $OccRoomsPercent_inc_Comps_minus_House_RoomsLYMTD) {
            $q->OccRoomsPercent_inc_Comps_minus_House_RoomsMTD = $OccRoomsPercent_inc_Comps_minus_House_RoomsMTD[0]->OccRoomsPercent_inc_Comps_minus_House_RoomsMTD;
            $q->OccRoomsPercent_inc_Comps_minus_House_RoomsYTD = $OccRoomsPercent_inc_Comps_minus_House_RoomsYTD[0]->OccRoomsPercent_inc_Comps_minus_House_RoomsYTD;
            $q->OccRoomsPercent_inc_Comps_minus_House_RoomsLYSD = $OccRoomsPercent_inc_Comps_minus_House_RoomsLYSD[0]->OccRoomsPercent_inc_Comps_minus_House_RoomsLYSD;
            $q->OccRoomsPercent_inc_Comps_minus_House_RoomsLYY = $OccRoomsPercent_inc_Comps_minus_House_RoomsLYY[0]->OccRoomsPercent_inc_Comps_minus_House_RoomsLYY;
            $q->OccRoomsPercent_inc_Comps_minus_House_RoomsLYMTD = $OccRoomsPercent_inc_Comps_minus_House_RoomsLYMTD[0]->OccRoomsPercent_inc_Comps_minus_House_RoomsLYMTD;
            return $q;
        });



        $RoomsOccupiedPercent_minus_Comps_Rooms = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(oc_rooms-comp_rooms)*100 as RoomsOccupiedPercent_minus_Comps_Rooms')->get();

        $RoomsOccupiedPercent_minus_Comps_RoomsMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(oc_rooms-comp_rooms)*100 as  RoomsOccupiedPercent_minus_Comps_RoomsMTD')->get();


        $RoomsOccupiedPercent_minus_Comps_RoomsYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('hotel_date,(oc_rooms-comp_rooms)*100  as RoomsOccupiedPercent_minus_Comps_RoomsYTD')->get();

        $RoomsOccupiedPercent_minus_Comps_RoomsLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(oc_rooms-comp_rooms)*100 as  RoomsOccupiedPercent_minus_Comps_RoomsLYSD')->get();

        $RoomsOccupiedPercent_minus_Comps_RoomsLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(oc_rooms-comp_rooms)*100  as RoomsOccupiedPercent_minus_Comps_RoomsLYY')->get();

        $RoomsOccupiedPercent_minus_Comps_RoomsLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(oc_rooms-comp_rooms)*100 as RoomsOccupiedPercent_minus_Comps_RoomsLYMTD')->get();

        $Total_Rooms_InHotel['RoomsOccupiedPercent_minus_Comps_Rooms'] = $RoomsOccupiedPercent_minus_Comps_Rooms->map(function ($q) use ($RoomsOccupiedPercent_minus_Comps_RoomsMTD, $RoomsOccupiedPercent_minus_Comps_Rooms, $RoomsOccupiedPercent_minus_Comps_RoomsYTD, $RoomsOccupiedPercent_minus_Comps_RoomsLYSD, $RoomsOccupiedPercent_minus_Comps_RoomsLYY, $RoomsOccupiedPercent_minus_Comps_RoomsLYMTD) {
            $q->RoomsOccupiedPercent_minus_Comps_RoomsMTD = $RoomsOccupiedPercent_minus_Comps_RoomsMTD[0]->RoomsOccupiedPercent_minus_Comps_RoomsMTD;
            $q->RoomsOccupiedPercent_minus_Comps_RoomsYTD = $RoomsOccupiedPercent_minus_Comps_RoomsYTD[0]->RoomsOccupiedPercent_minus_Comps_RoomsYTD;
            $q->RoomsOccupiedPercent_minus_Comps_RoomsLYSD = $RoomsOccupiedPercent_minus_Comps_RoomsLYSD[0]->RoomsOccupiedPercent_minus_Comps_RoomsLYSD;
            $q->RoomsOccupiedPercent_minus_Comps_RoomsLYY = $RoomsOccupiedPercent_minus_Comps_RoomsLYY[0]->RoomsOccupiedPercent_minus_Comps_RoomsLYY;
            $q->RoomsOccupiedPercent_minus_Comps_RoomsLYMTD = $RoomsOccupiedPercent_minus_Comps_RoomsLYMTD[0]->RoomsOccupiedPercent_minus_Comps_RoomsLYMTD;
            return $q;
        });


        $RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(oc_rooms-comp_rooms-oo_rooms) as RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO')->get();

        $RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_MTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(oc_rooms-comp_rooms-oo_rooms) as  RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_MTD')->get();


        $RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_YTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(oc_rooms-comp_rooms-oo_rooms)  as RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_YTD')->get();

        $RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_LYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(oc_rooms-comp_rooms-oo_rooms)as  RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_LYSD')->get();

        $RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_LYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(oc_rooms-comp_rooms-oo_rooms)  as RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_LYY')->get();

        $RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_LYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(oc_rooms-comp_rooms-oo_rooms)as RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_LYMTD')->get();

        $Total_Rooms_InHotel['RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO'] = $RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO->map(function ($q) use ($RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_MTD, $RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO, $RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_YTD, $RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_LYSD, $RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_LYY, $RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_LYMTD) {
            $q->RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_MTD = $RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_MTD[0]->RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_MTD;
            $q->RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_YTD = $RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_YTD[0]->RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_YTD;
            $q->RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_LYSD = $RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_LYSD[0]->RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_LYSD;
            $q->RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_LYY = $RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_LYY[0]->RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_LYY;
            $q->RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_LYMTD = $RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_LYMTD[0]->RoomsOccupiedPercent_minus_Comps_Rooms_minus_OOO_LYMTD;
            return $q;
        });

        $DoubleOCC_Percent = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(2*oc_rooms)*100 as DoubleOCC_Percent')->get();


        $DoubleOCC_PercentMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(2*oc_rooms)*100  as  DoubleOCC_PercentMTD')->get();


        $DoubleOCC_PercentYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(2*oc_rooms)*100   as DoubleOCC_PercentYTD')->get();

        $DoubleOCC_PercentLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(2*oc_rooms)*100 as  DoubleOCC_PercentLYSD')->get();

        $DoubleOCC_PercentLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(2*oc_rooms)*100   as DoubleOCC_PercentLYY')->get();

        $DoubleOCC_PercentLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(2*oc_rooms)*100  as DoubleOCC_PercentLYMTD')->get();

        $Total_Rooms_InHotel['DoubleOCC_Percent'] = $DoubleOCC_Percent->map(function ($q) use ($DoubleOCC_PercentMTD, $DoubleOCC_Percent, $DoubleOCC_PercentLYY, $DoubleOCC_PercentYTD, $DoubleOCC_PercentLYSD, $DoubleOCC_PercentLYMTD) {
            $q->DoubleOCC_PercentMTD = $DoubleOCC_PercentMTD[0]->DoubleOCC_PercentMTD;
            $q->DoubleOCC_PercentYTD = $DoubleOCC_PercentYTD[0]->DoubleOCC_PercentYTD;
            $q->DoubleOCC_PercentLYSD = $DoubleOCC_PercentLYSD[0]->DoubleOCC_PercentLYSD;
            $q->DoubleOCC_PercentLYY = $DoubleOCC_PercentLYY[0]->DoubleOCC_PercentLYY;
            $q->DoubleOCC_PercentLYMTD = $DoubleOCC_PercentLYMTD[0]->DoubleOCC_PercentLYMTD;
            return $q;
        });

        $RoomArrivals = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(act_chkin_rooms) as RoomArrivals')->get();


        $RoomArrivalsMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(act_chkin_rooms)  as  RoomArrivalsMTD')->get();


        $RoomArrivalsYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(act_chkin_rooms)   as RoomArrivalsYTD')->get();

        $RoomArrivalsLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(act_chkin_rooms) as  RoomArrivalsLYSD')->get();

        $RoomArrivalsLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(act_chkin_rooms)   as RoomArrivalsLYY')->get();

        $RoomArrivalsLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(act_chkin_rooms)  as RoomArrivalsLYMTD')->get();

        $Total_Rooms_InHotel['RoomArrivals'] = $RoomArrivals->map(function ($q) use ($RoomArrivalsMTD, $RoomArrivals, $RoomArrivalsLYY, $RoomArrivalsYTD, $RoomArrivalsLYSD, $RoomArrivalsLYMTD) {
            $q->RoomArrivalsMTD = $RoomArrivalsMTD[0]->RoomArrivalsMTD;
            $q->RoomArrivalsYTD = $RoomArrivalsYTD[0]->RoomArrivalsYTD;
            $q->RoomArrivalsLYSD = $RoomArrivalsLYSD[0]->RoomArrivalsLYSD;
            $q->RoomArrivalsLYY = $RoomArrivalsLYY[0]->RoomArrivalsLYY;
            $q->RoomArrivalsLYMTD = $RoomArrivalsLYMTD[0]->RoomArrivalsLYMTD;
            return $q;
        });

        $RoomArrivalsPax = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(act_chkin_pax) as RoomArrivalsPax')->get();


        $RoomArrivalsPaxMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(act_chkin_pax)  as  RoomArrivalsPaxMTD')->get();


        $RoomArrivalsPaxYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(act_chkin_pax)   as RoomArrivalsPaxYTD')->get();

        $RoomArrivalsPaxLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(act_chkin_pax) as  RoomArrivalsPaxLYSD')->get();

        $RoomArrivalsPaxLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(act_chkin_pax)   as RoomArrivalsPaxLYY')->get();

        $RoomArrivalsPaxLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(act_chkin_pax)  as RoomArrivalsPaxLYMTD')->get();

        $Total_Rooms_InHotel['RoomArrivalsPax'] = $RoomArrivalsPax->map(function ($q) use ($RoomArrivalsPaxMTD, $RoomArrivalsPax, $RoomArrivalsPaxLYY, $RoomArrivalsPaxYTD, $RoomArrivalsPaxLYSD, $RoomArrivalsPaxLYMTD) {
            $q->RoomArrivalsPaxMTD = $RoomArrivalsPaxMTD[0]->RoomArrivalsPaxMTD;
            $q->RoomArrivalsPaxYTD = $RoomArrivalsPaxYTD[0]->RoomArrivalsPaxYTD;
            $q->RoomArrivalsPaxLYSD = $RoomArrivalsPaxLYSD[0]->RoomArrivalsPaxLYSD;
            $q->RoomArrivalsPaxLYY = $RoomArrivalsPaxLYY[0]->RoomArrivalsPaxLYY;
            $q->RoomArrivalsLYMTD = $RoomArrivalsPaxLYMTD[0]->RoomArrivalsPaxLYMTD;
            return $q;
        });



        $RoomDepartures = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(act_chkout_rooms) as RoomDepartures')->get();


        $RoomDeparturesMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(act_chkout_rooms)  as  RoomDeparturesMTD')->get();


        $RoomDeparturesYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(act_chkout_rooms)   as RoomDeparturesYTD')->get();

        $RoomDeparturesLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(act_chkout_rooms) as  RoomDeparturesLYSD')->get();

        $RoomDeparturesLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(act_chkout_rooms)   as RoomDeparturesLYY')->get();

        $RoomDeparturesLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(act_chkout_rooms)  as RoomDeparturesLYMTD')->get();

        $Total_Rooms_InHotel['RoomDepartures'] = $RoomDepartures->map(function ($q) use ($RoomDeparturesMTD, $RoomDepartures, $RoomDeparturesLYY, $RoomDeparturesYTD, $RoomDeparturesLYSD, $RoomDeparturesLYMTD) {
            $q->RoomDeparturesMTD = $RoomDeparturesMTD[0]->RoomDeparturesMTD;
            $q->RoomDeparturesYTD = $RoomDeparturesYTD[0]->RoomDeparturesYTD;
            $q->RoomDeparturesLYSD = $RoomDeparturesLYSD[0]->RoomDeparturesLYSD;
            $q->RoomDeparturesLYY = $RoomDeparturesLYY[0]->RoomDeparturesLYY;
            $q->RoomDeparturesLYMTD = $RoomDeparturesLYMTD[0]->RoomDeparturesLYMTD;
            return $q;
        });

        $GroupGuest = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(guest_group) as GroupGuest')->get();

        $GroupGuestMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(guest_group)  as GroupGuestMTD')->get();


        $GroupGuestYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(guest_group)   as GroupGuestYTD')->get();

        $GroupGuestLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(guest_group) as  GroupGuestLYSD')->get();

        $GroupGuestLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(guest_group)   as GroupGuestLYY')->get();

        $GroupGuestLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(guest_group)  as GroupGuestLYMTD')->get();

        $Total_Rooms_InHotel['GroupGuest'] = $GroupGuest->map(function ($q) use ($GroupGuestMTD, $GroupGuest, $GroupGuestLYY, $GroupGuestYTD, $GroupGuestLYSD, $GroupGuestLYMTD) {
            $q->GroupGuestMTD = $GroupGuestMTD[0]->GroupGuestMTD;
            $q->GroupGuestYTD = $GroupGuestYTD[0]->GroupGuestYTD;
            $q->GroupGuestLYSD = $GroupGuestLYSD[0]->GroupGuestLYSD;
            $q->GroupGuestLYY = $GroupGuestLYY[0]->GroupGuestLYY;
            $q->GroupGuestLYMTD = $GroupGuestLYMTD[0]->GroupGuestLYMTD;
            return $q;
        });


        $RoomDeparturesPax = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(act_chkout_rooms) as RoomDeparturesPax')->get();

        $RoomDeparturesPaxMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(act_chkout_rooms)  as RoomDeparturesPaxMTD')->get();


        $RoomDeparturesPaxYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(act_chkout_rooms)   as RoomDeparturesPaxYTD')->get();

        $RoomDeparturesPaxLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(act_chkout_rooms) as  RoomDeparturesPaxLYSD')->get();

        $RoomDeparturesPaxLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(act_chkout_rooms)   as RoomDeparturesPaxLYY')->get();

        $RoomDeparturesPaxLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(act_chkout_rooms)  as RoomDeparturesPaxLYMTD')->get();

        $Total_Rooms_InHotel['RoomDeparturesPax'] = $RoomDeparturesPax->map(function ($q) use ($RoomDeparturesPaxMTD, $RoomDeparturesPax, $RoomDeparturesPaxLYY, $RoomDeparturesPaxYTD, $RoomDeparturesPaxLYSD, $RoomDeparturesPaxLYMTD) {
            $q->RoomDeparturesPaxMTD = $RoomDeparturesPaxMTD[0]->RoomDeparturesPaxMTD;
            $q->RoomDeparturesPaxYTD = $RoomDeparturesPaxYTD[0]->RoomDeparturesPaxYTD;
            $q->RoomDeparturesPaxLYSD = $RoomDeparturesPaxLYSD[0]->RoomDeparturesPaxLYSD;
            $q->RoomDeparturesPaxLYY = $RoomDeparturesPaxLYY[0]->RoomDeparturesPaxLYY;
            $q->RoomDeparturesPaxLYMTD = $RoomDeparturesPaxLYMTD[0]->RoomDeparturesPaxLYMTD;
            return $q;
        });


        $ReservationsMadeToday = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(res_today) as ReservationsMadeToday')->get();

        $ReservationsMadeTodayMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(res_today)  as ReservationsMadeTodayMTD')->get();


        $ReservationsMadeTodayYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(res_today)   as ReservationsMadeTodayYTD')->get();

        $ReservationsMadeTodayLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(res_today) as  ReservationsMadeTodayLYSD')->get();

        $ReservationsMadeTodayLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(res_today) as ReservationsMadeTodayLYY')->get();

        $ReservationsMadeTodayLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(res_today)  as ReservationsMadeTodayLYMTD')->get();


        $Total_Rooms_InHotel['ReservationsMadeToday'] = $ReservationsMadeToday->map(function ($q) use ($ReservationsMadeTodayMTD, $ReservationsMadeToday, $ReservationsMadeTodayLYY, $ReservationsMadeTodayYTD, $ReservationsMadeTodayLYSD, $ReservationsMadeTodayLYMTD) {
            $q->ReservationsMadeTodayMTD = $ReservationsMadeTodayMTD[0]->ReservationsMadeTodayMTD;
            $q->ReservationsMadeTodayYTD = $ReservationsMadeTodayYTD[0]->ReservationsMadeTodayYTD;
            $q->ReservationsMadeTodayLYSD = $ReservationsMadeTodayLYSD[0]->ReservationsMadeTodayLYSD;
            $q->ReservationsMadeTodayLYY = $ReservationsMadeTodayLYY[0]->ReservationsMadeTodayLYY;
            $q->ReservationsMadeTodayLYMTD = $ReservationsMadeTodayLYMTD[0]->ReservationsMadeTodayLYMTD;
            return $q;
        });

        $CancellationsMadeToday = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(res_cancel) as CancellationsMadeToday')->get();

        $CancellationsMadeTodayMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(res_cancel)  as CancellationsMadeTodayMTD')->get();


        $CancellationsMadeTodayYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(res_cancel)   as CancellationsMadeTodayYTD')->get();

        $CancellationsMadeTodayLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(res_cancel) as  CancellationsMadeTodayLYSD')->get();

        $CancellationsMadeTodayLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(res_cancel) as CancellationsMadeTodayLYY')->get();

        $CancellationsMadeTodayLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(res_cancel)  as CancellationsMadeTodayLYMTD')->get();

        $Total_Rooms_InHotel['CancellationsMadeToday'] = $CancellationsMadeToday->map(function ($q) use ($CancellationsMadeTodayMTD, $CancellationsMadeToday, $CancellationsMadeTodayLYY, $CancellationsMadeTodayYTD, $CancellationsMadeTodayLYSD, $CancellationsMadeTodayLYMTD) {
            $q->CancellationsMadeTodayMTD = $CancellationsMadeTodayMTD[0]->CancellationsMadeTodayMTD;
            $q->CancellationsMadeTodayYTD = $CancellationsMadeTodayYTD[0]->CancellationsMadeTodayYTD;
            $q->CancellationsMadeTodayLYSD = $CancellationsMadeTodayLYSD[0]->CancellationsMadeTodayLYSD;
            $q->CancellationsMadeTodayLYY = $CancellationsMadeTodayLYY[0]->CancellationsMadeTodayLYY;
            $q->CancellationsMadeTodayLYMTD = $CancellationsMadeTodayLYMTD[0]->CancellationsMadeTodayLYMTD;
            return $q;
        });

        $NoShow = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(res_voshow_rooms) as NoShow')->get();

        $NoShowMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(res_voshow_rooms)  as NoShowMTD')->get();


        $NoShowYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(res_voshow_rooms)   as NoShowYTD')->get();

        $NoShowLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(res_voshow_rooms) as  NoShowLYSD')->get();

        $NoShowLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(res_voshow_rooms) as NoShowLYY')->get();

        $NoShowLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(res_voshow_rooms)  as NoShowLYMTD')->get();

        $Total_Rooms_InHotel['NoShow'] = $NoShow->map(function ($q) use ($NoShowMTD, $NoShow, $NoShowLYY, $NoShowYTD, $NoShowLYSD, $NoShowLYMTD) {
            $q->NoShowMTD = $NoShowMTD[0]->NoShowMTD;
            $q->NoShowYTD = $NoShowYTD[0]->NoShowYTD;
            $q->NoShowLYSD = $NoShowLYSD[0]->NoShowLYSD;
            $q->NoShowLYY = $NoShowLYY[0]->NoShowLYY;
            $q->NoShowLYMTD = $NoShowLYMTD[0]->NoShowLYMTD;
            return $q;
        });


        $NoShowPax = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(res_noshow_pax) as NoShowPax')->get();

        $NoShowPaxMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(res_noshow_pax)  as NoShowPaxMTD')->get();


        $NoShowPaxYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(res_noshow_pax)   as NoShowPaxYTD')->get();

        $NoShowPaxLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(res_noshow_pax) as  NoShowPaxLYSD')->get();

        $NoShowPaxLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(res_noshow_pax) as NoShowPaxLYY')->get();

        $NoShowPaxLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(res_noshow_pax)  as NoShowPaxLYMTD')->get();

        $Total_Rooms_InHotel['NoShowPax'] = $NoShowPax->map(function ($q) use ($NoShowPaxMTD, $NoShowPax, $NoShowPaxLYY, $NoShowPaxYTD, $NoShowPaxLYSD, $NoShowPaxLYMTD) {
            $q->NoShowPaxMTD = $NoShowPaxMTD[0]->NoShowPaxMTD;
            $q->NoShowPaxYTD = $NoShowPaxYTD[0]->NoShowPaxYTD;
            $q->NoShowPaxLYSD = $NoShowPaxLYSD[0]->NoShowPaxLYSD;
            $q->NoShowPaxLYY = $NoShowPaxLYY[0]->NoShowPaxLYY;
            $q->NoShowPaxLYMTD = $NoShowPaxLYMTD[0]->NoShowPaxLYMTD;
            return $q;
        });


        $EarlyDepartures = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(early_chkout_rooms) as EarlyDepartures')->get();

        $EarlyDeparturesMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(early_chkout_rooms)  as EarlyDeparturesMTD')->get();


        $EarlyDeparturesYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(early_chkout_rooms)   as EarlyDeparturesYTD')->get();

        $EarlyDeparturesLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(early_chkout_rooms) as  EarlyDeparturesLYSD')->get();

        $EarlyDeparturesLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(early_chkout_rooms) as EarlyDeparturesLYY')->get();

        $EarlyDeparturesLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(early_chkout_rooms)  as EarlyDeparturesLYMTD')->get();

        $Total_Rooms_InHotel['EarlyDepartures'] = $EarlyDepartures->map(function ($q) use ($EarlyDeparturesMTD, $EarlyDepartures, $EarlyDeparturesLYY, $EarlyDeparturesYTD, $EarlyDeparturesLYSD, $EarlyDeparturesLYMTD) {
            $q->EarlyDeparturesMTD = $EarlyDeparturesMTD[0]->EarlyDeparturesMTD;
            $q->EarlyDeparturesYTD = $EarlyDeparturesYTD[0]->EarlyDeparturesYTD;
            $q->EarlyDeparturesLYSD = $EarlyDeparturesLYSD[0]->EarlyDeparturesLYSD;
            $q->EarlyDeparturesLYY = $EarlyDeparturesLYY[0]->EarlyDeparturesLYY;
            $q->EarlyDeparturesLYMTD = $EarlyDeparturesLYMTD[0]->EarlyDeparturesLYMTD;
            return $q;
        });


        $EarlyDeparturePax = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(early_chkout_pax) as EarlyDeparturesPax')->get();
        $EarlyDeparturesPaxMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(early_chkout_pax)  as EarlyDeparturesPaxMTD')->get();


        $EarlyDeparturesPaxYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(early_chkout_pax)   as EarlyDeparturesPaxYTD')->get();

        $EarlyDeparturesPaxLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(early_chkout_pax) as  EarlyDeparturesPaxLYSD')->get();

        $EarlyDeparturesPaxLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(early_chkout_pax) as EarlyDeparturesPaxLYY')->get();

        $EarlyDeparturesPaxLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(early_chkout_pax)  as EarlyDeparturesPaxLYMTD')->get();

        $Total_Rooms_InHotel['EarlyDeparturePax'] = $EarlyDeparturePax->map(function ($q) use ($EarlyDeparturesPaxMTD, $EarlyDeparturePax, $EarlyDeparturesPaxLYY, $EarlyDeparturesPaxYTD, $EarlyDeparturesPaxLYSD, $EarlyDeparturesPaxLYMTD) {
            $q->EarlyDeparturesPaxMTD = $EarlyDeparturesPaxMTD[0]->EarlyDeparturesPaxMTD;
            $q->EarlyDeparturesPaxYTD = $EarlyDeparturesPaxYTD[0]->EarlyDeparturesPaxYTD;
            $q->EarlyDeparturesPaxLYSD = $EarlyDeparturesPaxLYSD[0]->EarlyDeparturesPaxLYSD;
            $q->EarlyDeparturesPaxLYY = $EarlyDeparturesPaxLYY[0]->EarlyDeparturesPaxLYY;
            $q->EarlyDeparturesPaxLYMTD = $EarlyDeparturesPaxLYMTD[0]->EarlyDeparturesPaxLYMTD;
            return $q;
        });




        $ExtendStay = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(extended_pax) as ExtendStay')->get();

        $ExtendStayMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(extended_pax)  as ExtendStayMTD')->get();


        $ExtendStayYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(extended_pax)   as ExtendStayYTD')->get();

        $ExtendStayLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(extended_pax) as  ExtendStayLYSD')->get();

        $ExtendStayLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(extended_pax) as ExtendStayLYY')->get();

        $ExtendStayLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(extended_pax)  as ExtendStayLYMTD')->get();

        $Total_Rooms_InHotel['ExtendStay'] = $ExtendStay->map(function ($q) use ($ExtendStayMTD, $ExtendStay, $ExtendStayLYY, $ExtendStayYTD, $ExtendStayLYSD, $ExtendStayLYMTD) {
            $q->ExtendStayMTD = $ExtendStayMTD[0]->ExtendStayMTD;
            $q->ExtendStayYTD = $ExtendStayYTD[0]->ExtendStayYTD;
            $q->ExtendStayLYSD = $ExtendStayLYSD[0]->ExtendStayLYSD;
            $q->ExtendStayLYY = $ExtendStayLYY[0]->ExtendStayLYY;
            $q->ExtendStayLYMTD = $ExtendStayLYMTD[0]->ExtendStayLYMTD;
            return $q;
        });




        $ExtendStayPax = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(extended_pax) as ExtendStayPax')->get();

        $ExtendStayPaxMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(extended_pax)  as ExtendStayPaxMTD')->get();


        $ExtendStayPaxYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(extended_pax)   as ExtendStayPaxYTD')->get();

        $ExtendStayPaxLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(extended_pax) as  ExtendStayPaxLYSD')->get();

        $ExtendStayPaxLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(extended_pax) as ExtendStayPaxLYY')->get();

        $ExtendStayPaxLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(extended_pax)  as ExtendStayPaxLYMTD')->get();

        $Total_Rooms_InHotel['ExtendStayPax'] = $ExtendStayPax->map(function ($q) use ($ExtendStayPaxMTD, $ExtendStayPax, $ExtendStayPaxLYY, $ExtendStayPaxYTD, $ExtendStayPaxLYSD, $ExtendStayPaxLYMTD) {
            $q->ExtendStayPaxMTD = $ExtendStayPaxMTD[0]->ExtendStayPaxMTD;
            $q->ExtendStayPaxYTD = $ExtendStayPaxYTD[0]->ExtendStayPaxYTD;
            $q->ExtendStayPaxLYSD = $ExtendStayPaxLYSD[0]->ExtendStayPaxLYSD;
            $q->ExtendStayPaxLYY = $ExtendStayPaxLYY[0]->ExtendStayPaxLYY;
            $q->ExtendStayPaxLYMTD = $ExtendStayPaxLYMTD[0]->ExtendStayPaxLYMTD;
            return $q;
        });


        $WalkIn_Rooms = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(walkin_rooms) as WalkIn_Rooms')->get();

        $WalkIn_RoomsMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(walkin_rooms)  as WalkIn_RoomsMTD')->get();


        $WalkIn_RoomsYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(walkin_rooms)   as WalkIn_RoomsYTD')->get();

        $WalkIn_RoomsLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(walkin_rooms) as  WalkIn_RoomsLYSD')->get();

        $WalkIn_RoomsLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(walkin_rooms) as WalkIn_RoomsLYY')->get();

        $WalkIn_RoomsLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(walkin_rooms)  as WalkIn_RoomsLYMTD')->get();

        $Total_Rooms_InHotel['WalkIn_Rooms'] = $WalkIn_Rooms->map(function ($q) use ($WalkIn_RoomsMTD, $WalkIn_Rooms, $WalkIn_RoomsLYY, $WalkIn_RoomsYTD, $WalkIn_RoomsLYSD, $WalkIn_RoomsLYMTD) {
            $q->WalkIn_RoomsMTD = $WalkIn_RoomsMTD[0]->WalkIn_RoomsMTD;
            $q->WalkIn_RoomsYTD = $WalkIn_RoomsYTD[0]->WalkIn_RoomsYTD;
            $q->WalkIn_RoomsLYSD = $WalkIn_RoomsLYSD[0]->WalkIn_RoomsLYSD;
            $q->WalkIn_RoomsLYY = $WalkIn_RoomsLYY[0]->WalkIn_RoomsLYY;
            $q->WalkIn_RoomsLYMTD = $WalkIn_RoomsLYMTD[0]->WalkIn_RoomsLYMTD;
            return $q;
        });



        $WalkIn_RoomsPax = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(walkin_pax) as WalkIn_RoomsPax')->get();

        $WalkIn_RoomsPaxMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(walkin_pax)  as WalkIn_RoomsPaxMTD')->get();


        $WalkIn_RoomsPaxYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(walkin_pax)   as WalkIn_RoomsPaxYTD')->get();

        $WalkIn_RoomsPaxLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(walkin_pax) as  WalkIn_RoomsPaxLYSD')->get();

        $WalkIn_RoomsPaxLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(walkin_pax) as WalkIn_RoomsPaxLYY')->get();

        $WalkIn_RoomsPaxLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(walkin_pax)  as WalkIn_RoomsPaxLYMTD')->get();


        $Total_Rooms_InHotel['WalkIn_RoomsPax'] = $WalkIn_RoomsPax->map(function ($q) use ($WalkIn_RoomsPaxMTD, $WalkIn_RoomsPax, $WalkIn_RoomsPaxLYY, $WalkIn_RoomsPaxYTD, $WalkIn_RoomsPaxLYSD, $WalkIn_RoomsPaxLYMTD) {
            $q->WalkIn_RoomsPaxMTD = $WalkIn_RoomsPaxMTD[0]->WalkIn_RoomsPaxMTD;
            $q->WalkIn_RoomsPaxYTD = $WalkIn_RoomsPaxYTD[0]->WalkIn_RoomsPaxYTD;
            $q->WalkIn_RoomsPaxLYSD = $WalkIn_RoomsPaxLYSD[0]->WalkIn_RoomsPaxLYSD;
            $q->WalkIn_RoomsPaxLYY = $WalkIn_RoomsPaxLYY[0]->WalkIn_RoomsPaxLYY;
            $q->WalkIn_RoomsPaxLYMTD = $WalkIn_RoomsPaxLYMTD[0]->WalkIn_RoomsPaxLYMTD;
            return $q;
        });


        $DayUse = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(day_use_rooms) as DayUse')->get();

        $DayUseMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(day_use_rooms)  as DayUseMTD')->get();


        $DayUseYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(day_use_rooms)   as DayUseYTD')->get();

        $DayUseLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(day_use_rooms) as  DayUseLYSD')->get();

        $DayUseLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(day_use_rooms) as DayUseLYY')->get();

        $DayUseLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(day_use_rooms)  as DayUseLYMTD')->get();

        $Total_Rooms_InHotel['DayUse'] = $DayUse->map(function ($q) use ($DayUseMTD, $DayUse, $DayUseLYY, $DayUseYTD, $DayUseLYSD, $DayUseLYMTD) {
            $q->DayUseMTD = $DayUseMTD[0]->DayUseMTD;
            $q->DayUseYTD = $DayUseYTD[0]->DayUseYTD;
            $q->DayUseLYSD = $DayUseLYSD[0]->DayUseLYSD;
            $q->DayUseLYY = $DayUseLYY[0]->DayUseLYY;
            $q->DayUseLYMTD = $DayUseLYMTD[0]->DayUseLYMTD;
            return $q;
        });



        $ArrivalsTomorrowRooms = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(expt_in_tmrw_rooms) as ArrivalsTomorrowRooms')->get();

        $ArrivalsTomorrowRoomsMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(expt_in_tmrw_rooms)  as ArrivalsTomorrowRoomsMTD')->get();


        $ArrivalsTomorrowRoomsYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(expt_in_tmrw_rooms)   as ArrivalsTomorrowRoomsYTD')->get();

        $ArrivalsTomorrowRoomsLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(expt_in_tmrw_rooms) as  ArrivalsTomorrowRoomsLYSD')->get();

        $ArrivalsTomorrowRoomsLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(expt_in_tmrw_rooms) as ArrivalsTomorrowRoomsLYY')->get();

        $ArrivalsTomorrowRoomsLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(expt_in_tmrw_rooms)  as ArrivalsTomorrowRoomsLYMTD')->get();

        $Total_Rooms_InHotel['ArrivalsTomorrowRooms'] = $ArrivalsTomorrowRooms->map(function ($q) use ($ArrivalsTomorrowRoomsMTD, $ArrivalsTomorrowRooms, $ArrivalsTomorrowRoomsLYY, $ArrivalsTomorrowRoomsYTD, $ArrivalsTomorrowRoomsLYSD, $ArrivalsTomorrowRoomsLYMTD) {
            $q->ArrivalsTomorrowRoomsMTD = $ArrivalsTomorrowRoomsMTD[0]->ArrivalsTomorrowRoomsMTD;
            $q->ArrivalsTomorrowRoomsYTD = $ArrivalsTomorrowRoomsYTD[0]->ArrivalsTomorrowRoomsYTD;
            $q->ArrivalsTomorrowRoomsLYSD = $ArrivalsTomorrowRoomsLYSD[0]->ArrivalsTomorrowRoomsLYSD;
            $q->ArrivalsTomorrowRoomsLYY = $ArrivalsTomorrowRoomsLYY[0]->ArrivalsTomorrowRoomsLYY;
            $q->ArrivalsTomorrowRoomsLYMTD = $ArrivalsTomorrowRoomsLYMTD[0]->ArrivalsTomorrowRoomsLYMTD;
            return $q;
        });


        $ArrivalsTomorrowRoomsPax = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(expt_in_tmrw_pax) as ArrivalsTomorrowRoomsPax')->get();

        $ArrivalsTomorrowRoomsPaxMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(expt_in_tmrw_pax)  as ArrivalsTomorrowRoomsPaxMTD')->get();


        $ArrivalsTomorrowRoomsPaxYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(expt_in_tmrw_pax)   as ArrivalsTomorrowRoomsPaxYTD')->get();

        $ArrivalsTomorrowRoomsPaxLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(expt_in_tmrw_pax) as  ArrivalsTomorrowRoomsPaxLYSD')->get();

        $ArrivalsTomorrowRoomsPaxLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(expt_in_tmrw_pax) as ArrivalsTomorrowRoomsPaxLYY')->get();

        $ArrivalsTomorrowRoomsPaxLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(expt_in_tmrw_pax)  as ArrivalsTomorrowRoomsPaxLYMTD')->get();

        $Total_Rooms_InHotel['ArrivalsTomorrowRoomsPax'] = $ArrivalsTomorrowRoomsPax->map(function ($q) use ($ArrivalsTomorrowRoomsPaxMTD, $ArrivalsTomorrowRoomsPax, $ArrivalsTomorrowRoomsPaxLYY, $ArrivalsTomorrowRoomsPaxYTD, $ArrivalsTomorrowRoomsPaxLYSD, $ArrivalsTomorrowRoomsPaxLYMTD) {
            $q->ArrivalsTomorrowRoomsPaxMTD = $ArrivalsTomorrowRoomsPaxMTD[0]->ArrivalsTomorrowRoomsPaxMTD;
            $q->ArrivalsTomorrowRoomsPaxYTD = $ArrivalsTomorrowRoomsPaxYTD[0]->ArrivalsTomorrowRoomsPaxYTD;
            $q->ArrivalsTomorrowRoomsPaxLYSD = $ArrivalsTomorrowRoomsPaxLYSD[0]->ArrivalsTomorrowRoomsPaxLYSD;
            $q->ArrivalsTomorrowRoomsPaxLYY = $ArrivalsTomorrowRoomsPaxLYY[0]->ArrivalsTomorrowRoomsPaxLYY;
            $q->ArrivalsTomorrowRoomsPaxLYMTD = $ArrivalsTomorrowRoomsPaxLYMTD[0]->ArrivalsTomorrowRoomsPaxLYMTD;
            return $q;
        });

        $DeparturesTomorrowRooms = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(expt_out_tmrw_rooms) as DeparturesTomorrowRooms')->get();

        $DeparturesTomorrowRoomsMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(expt_out_tmrw_rooms)  as DeparturesTomorrowRoomsMTD')->get();


        $DeparturesTomorrowRoomsYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(expt_out_tmrw_rooms)   as DeparturesTomorrowRoomsYTD')->get();

        $DeparturesTomorrowRoomsLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(expt_out_tmrw_rooms) as  DeparturesTomorrowRoomsLYSD')->get();

        $DeparturesTomorrowRoomsLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(expt_out_tmrw_rooms) as DeparturesTomorrowRoomsLYY')->get();

        $DeparturesTomorrowRoomsLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(expt_out_tmrw_rooms)  as DeparturesTomorrowRoomsLYMTD')->get();

        $Total_Rooms_InHotel['DeparturesTomorrowRooms'] = $DeparturesTomorrowRooms->map(function ($q) use ($DeparturesTomorrowRoomsMTD, $DeparturesTomorrowRooms, $DeparturesTomorrowRoomsLYY, $DeparturesTomorrowRoomsYTD, $DeparturesTomorrowRoomsLYSD, $DeparturesTomorrowRoomsLYMTD) {
            $q->DeparturesTomorrowRoomsMTD = $DeparturesTomorrowRoomsMTD[0]->DeparturesTomorrowRoomsMTD;
            $q->DeparturesTomorrowRoomsYTD = $DeparturesTomorrowRoomsYTD[0]->DeparturesTomorrowRoomsYTD;
            $q->DeparturesTomorrowRoomsLYSD = $DeparturesTomorrowRoomsLYSD[0]->DeparturesTomorrowRoomsLYSD;
            $q->DeparturesTomorrowRoomsLYY = $DeparturesTomorrowRoomsLYY[0]->DeparturesTomorrowRoomsLYY;
            $q->DeparturesTomorrowRoomsLYMTD = $DeparturesTomorrowRoomsLYMTD[0]->DeparturesTomorrowRoomsLYMTD;
            return $q;
        });

        $DeparturesTomorrowRoomsPax = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(expt_out_tmrw_pax) as DeparturesTomorrowRoomsPax')->get();

        $DeparturesTomorrowRoomsPaxMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(expt_out_tmrw_pax)  as DeparturesTomorrowRoomsPaxMTD')->get();


        $DeparturesTomorrowRoomsPaxYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(expt_out_tmrw_pax)   as DeparturesTomorrowRoomsPaxYTD')->get();

        $DeparturesTomorrowRoomsPaxLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(expt_out_tmrw_pax) as  DeparturesTomorrowRoomsPaxLYSD')->get();

        $DeparturesTomorrowRoomsPaxLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(expt_out_tmrw_pax) as DeparturesTomorrowRoomsPaxLYY')->get();

        $DeparturesTomorrowRoomsPaxLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(expt_out_tmrw_pax)  as DeparturesTomorrowRoomsPaxLYMTD')->get();

        $Total_Rooms_InHotel['DeparturesTomorrowRoomsPax'] = $DeparturesTomorrowRoomsPax->map(function ($q) use ($DeparturesTomorrowRoomsPaxMTD, $DeparturesTomorrowRoomsPax, $DeparturesTomorrowRoomsPaxLYY, $DeparturesTomorrowRoomsPaxYTD, $DeparturesTomorrowRoomsPaxLYSD, $DeparturesTomorrowRoomsPaxLYMTD) {
            $q->DeparturesTomorrowRoomsPaxMTD = $DeparturesTomorrowRoomsPaxMTD[0]->DeparturesTomorrowRoomsPaxMTD;
            $q->DeparturesTomorrowRoomsPaxYTD = $DeparturesTomorrowRoomsPaxYTD[0]->DeparturesTomorrowRoomsPaxYTD;
            $q->DeparturesTomorrowRoomsPaxLYSD = $DeparturesTomorrowRoomsPaxLYSD[0]->DeparturesTomorrowRoomsPaxLYSD;
            $q->DeparturesTomorrowRoomsPaxLYY = $DeparturesTomorrowRoomsPaxLYY[0]->DeparturesTomorrowRoomsPaxLYY;
            $q->DeparturesTomorrowRoomsPaxLYMTD = $DeparturesTomorrowRoomsPaxLYMTD[0]->DeparturesTomorrowRoomsPaxLYMTD;
            return $q;
        });

        $OccupancyTomorrowPercent = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum((oc_rooms+expt_in_tmrw_rooms)-expt_out_tmrw_rooms)*100 as OccupancyTomorrowPercent')->get();

        $OccupancyTomorrowPercentMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum((oc_rooms+expt_in_tmrw_rooms)-expt_out_tmrw_rooms)*100  as OccupancyTomorrowPercentMTD')->get();


        $OccupancyTomorrowPercentYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum((oc_rooms+expt_in_tmrw_rooms)-expt_out_tmrw_rooms)*100   as OccupancyTomorrowPercentYTD')->get();

        $OccupancyTomorrowPercentLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum((oc_rooms+expt_in_tmrw_rooms)-expt_out_tmrw_rooms)*100 as  OccupancyTomorrowPercentLYSD')->get();

        $OccupancyTomorrowPercentLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum((oc_rooms+expt_in_tmrw_rooms)-expt_out_tmrw_rooms)*100 as OccupancyTomorrowPercentLYY')->get();

        $OccupancyTomorrowPercentLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum((oc_rooms+expt_in_tmrw_rooms)-expt_out_tmrw_rooms)*100 as OccupancyTomorrowPercentLYMTD')->get();

        $Total_Rooms_InHotel['OccupancyTomorrowPercent'] = $OccupancyTomorrowPercent->map(function ($q) use ($OccupancyTomorrowPercentMTD, $OccupancyTomorrowPercent, $OccupancyTomorrowPercentLYY, $OccupancyTomorrowPercentYTD, $OccupancyTomorrowPercentLYSD, $OccupancyTomorrowPercentLYMTD) {
            $q->OccupancyTomorrowPercentMTD = $OccupancyTomorrowPercentMTD[0]->OccupancyTomorrowPercentMTD;
            $q->OccupancyTomorrowPercentYTD = $OccupancyTomorrowPercentYTD[0]->OccupancyTomorrowPercentYTD;
            $q->OccupancyTomorrowPercentLYSD = $OccupancyTomorrowPercentLYSD[0]->OccupancyTomorrowPercentLYSD;
            $q->OccupancyTomorrowPercentLYY = $OccupancyTomorrowPercentLYY[0]->OccupancyTomorrowPercentLYY;
            $q->OccupancyTomorrowPercentLYMTD = $OccupancyTomorrowPercentLYMTD[0]->OccupancyTomorrowPercentLYMTD;
            return $q;
        });

        $AverageRoomRate = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(total_rate_room/guest_inhouse_pax) as AverageRoomRate')->get();


        $AverageRoomRateMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(total_rate_room/guest_inhouse_pax)  as AverageRoomRateMTD')->get();


        $AverageRoomRateYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(total_rate_room/guest_inhouse_pax)   as AverageRoomRateYTD')->get();

        $AverageRoomRateLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(total_rate_room/guest_inhouse_pax) as  AverageRoomRateLYSD')->get();

        $AverageRoomRateLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(total_rate_room/guest_inhouse_pax) as AverageRoomRateLYY')->get();

        $AverageRoomRateLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(total_rate_room/guest_inhouse_pax) as AverageRoomRateLYMTD')->get();

        $Total_Rooms_InHotel['AverageRoomRate'] = $AverageRoomRate->map(function ($q) use ($AverageRoomRateMTD, $AverageRoomRate, $AverageRoomRateLYY, $AverageRoomRateYTD, $AverageRoomRateLYSD, $AverageRoomRateLYMTD) {
            $q->AverageRoomRateMTD = $AverageRoomRateMTD[0]->AverageRoomRateMTD;
            $q->AverageRoomRateYTD = $AverageRoomRateYTD[0]->AverageRoomRateYTD;
            $q->AverageRoomRateLYSD = $AverageRoomRateLYSD[0]->AverageRoomRateLYSD;
            $q->AverageRoomRateLYY = $AverageRoomRateLYY[0]->AverageRoomRateLYY;
            $q->AverageRoomRateLYMTD = $AverageRoomRateLYMTD[0]->AverageRoomRateLYMTD;
            return $q;
        });


        $AveragePersonRate = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(total_rate_room/oc_rooms) as AveragePersonRate')->get();


        $AveragePersonRateMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(total_rate_room/oc_rooms)  as AveragePersonRateMTD')->get();


        $AveragePersonRateYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(total_rate_room/oc_rooms)  as AveragePersonRateYTD')->get();

        $AveragePersonRateLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(total_rate_room/oc_rooms) as  AveragePersonRateLYSD')->get();

        $AveragePersonRateLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(total_rate_room/oc_rooms) as AveragePersonRateLYY')->get();

        $AveragePersonRateLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(total_rate_room/oc_rooms) as AveragePersonRateLYMTD')->get();

        $Total_Rooms_InHotel['AveragePersonRate'] = $AveragePersonRate->map(function ($q) use ($AveragePersonRateMTD, $AveragePersonRate, $AveragePersonRateLYY, $AveragePersonRateYTD, $AveragePersonRateLYSD, $AveragePersonRateLYMTD) {
            $q->AveragePersonRateMTD = $AveragePersonRateMTD[0]->AveragePersonRateMTD;
            $q->AveragePersonRateYTD = $AveragePersonRateYTD[0]->AveragePersonRateYTD;
            $q->AveragePersonRateLYSD = $AveragePersonRateLYSD[0]->AveragePersonRateLYSD;
            $q->DeparturesTomorrowRoomsPaxLYY = $AveragePersonRateLYY[0]->AveragePersonRateLYY;
            $q->AveragePersonRateLYMTD = $AveragePersonRateLYMTD[0]->AveragePersonRateLYMTD;
            return $q;
        });

        return response()->json([
            'data' => [

                'manager_report'                                               => $manager_report,
                'Manager_Report1_Calculations'                                 => $Total_Rooms_InHotel,
            ]
        ]);
    }

    public function Manager5_Report(Request $request)
    {

        $Calculations = [];
        $MTDMonths = '';
        $Months_Arr = [
            'Jan' => "one",
            'Feb' => "tow",
            'Mar' => "three",
            'Apr' => "four",
            'May' => "five",
            'Jun' => "six",
            'Jul' => "seven",
            'Aug' => "eight",
            'Sep' => "nine",
            'Oct' => "ten",
            'Nov' => "eleven",
            'Dec' => "twelve",
        ];
        $Months_ArrNum = [
            '01' => "one",
            '02' => "tow",
            '03' => "three",
            '04' => "four",
            '05' => "five",
            '06' => "six",
            '07' => "seven",
            '08' => "eight",
            '09' => "nine",
            '10' => "ten",
            '11' => "eleven",
            '12' => "twelve",
        ];
        $jan = 'one';
        $feb = 'tow';
        $mar = 'three';
        $apr = 'four';
        $may = 'five';
        $jun = 'six';
        $jul = 'seven';
        $aug = 'eight';
        $sep = 'nine';
        $oct = 'ten';
        $nov = 'eleven';
        $dec = 'twelve';
        $monthString = Carbon::parse($request->date)->format('M');
        $monthNumber = Carbon::parse($request->date)->format('m');


        $monthsToDate = '';
        foreach ($Months_ArrNum as $key => $mon) {
            // dd($key);
            if ($key <= intVal($monthNumber)) {
                if ($key == '01') {
                    $MTDMonths = 'one';
                } else {
                    $MTDMonths = $MTDMonths . '+' . $mon;
                };
            };
        }

        // dd($monthString);
        $month = $Months_Arr[$monthString];

        $manager_report5 = ledgerCat::with(['ledgers' => function ($qu) use ($request, $month, $MTDMonths) {
            $qu->with(['transactions' => function ($que) use ($request) {

                $que->whereDate('hotel_date', $request->date);
                $que->selectRaw('sum(charge_amount) as charge_amount,ledger_id')->groupBy('ledger_id');
                $que->selectRaw('sum(payment_amount) as payment_amount,ledger_id');
            }])
                ->with(['transactionsMtd' =>  function ($que2) use ($request) {

                    $que2->whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date]);
                    $que2->selectRaw('sum(charge_amount) as charge_amount_MTD,   ledger_id')->groupBy('ledger_id');
                    $que2->selectRaw('sum(payment_amount) as payment_amount_MTD, ledger_id');
                }])
                ->with(['transactionsYtd' => function ($que3) use ($request) {

                    $que3->whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date]);
                    $que3->selectRaw('sum(charge_amount)as   charge_amount_YTD,   ledger_id')->groupBy('ledger_id');
                    $que3->selectRaw('sum(payment_amount) as  payment_amount_YTD, ledger_id');
                }])
                ->with(['Budgets' => function ($q) use ($request, $month, $MTDMonths) {
                    $q->select('ledger_id', $month)
                        ->selectRaw('(' . $MTDMonths . ') as YTD')
                        ->whereYear('year', $request->date);
                }]);
        }])

            ->with(['transactionsLedCat' => function ($query) use ($request) {

                $query->whereDate('hotel_date', $request->date);
                $query->selectRaw('sum(charge_amount) as   charge_amount_by_ledCat,  ledger_cat_id')->groupBy('ledger_cat_id');
                $query->selectRaw('sum(payment_amount) as  payment_amount_by_ledCat, ledger_cat_id');
            }])
            ->with(['transactionsMtdLedCat' =>  function ($query2) use ($request) {

                $query2->whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date]);
                $query2->selectRaw('sum(charge_amount) as  charge_amount_MTD_by_ledCat,    ledger_cat_id')->groupBy('ledger_cat_id');
                $query2->selectRaw('sum(payment_amount) as payment_amount_MTD_by_ledCat,   ledger_cat_id');
            }])
            ->with(['transactionsYtdLedCat' =>  function ($query3) use ($request) {

                $query3->whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date]);
                $query3->selectRaw('sum(charge_amount) as  charge_amount_YTD_by_ledCat,   ledger_cat_id')->groupBy('ledger_cat_id');
                $query3->selectRaw('sum(payment_amount) as payment_amount_YTD_by_ledCat,  ledger_cat_id');
            }])
            ->get();


        $guestHouse = DayCloseRecord::whereDate('hotel_date', $request->date)->selectRaw('(guest_inhouse_pax) as Guests_InHouse')->get();


        $guestHouseMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(guest_inhouse_pax) as guestHouseMTD')->get();

        $guestHouseYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(guest_inhouse_pax) as guestHouseYTD')->get();

        $guestHouseLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(guest_inhouse_pax) as guestHouseLYSD')->get();


        $guestHouseLYY = DayCloseRecord::selectRaw('sum(guest_inhouse_pax)as guestHouseLYY')
            ->whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->get();

        $guestHouseLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(guest_inhouse_pax) as guestHouseLYMTD')->get();

        $Calculations['guest_House'] = $guestHouse->map(function ($q) use ($guestHouseMTD, $guestHouse, $guestHouseYTD, $guestHouseLYSD, $guestHouseLYY, $guestHouseLYMTD) {
            $q->guestHouseMTD = $guestHouseMTD[0]->guestHouseMTD;
            $q->guestHouseYTD = $guestHouseYTD[0]->guestHouseYTD;
            $q->guestHouseLYSD = $guestHouseLYSD[0]->guestHouseLYSD;
            $q->guestHouseLYY = $guestHouseLYY[0]->guestHouseLYY;
            $q->guestHouseLYMTD = $guestHouseLYMTD[0]->guestHouseLYMTD;
            return $q;
        });

        $TotalRooms = DayCloseRecord::whereDate('hotel_date', $request->date)->selectRaw('(total_room) as TotalRooms')->get();

        $TotalRoomsMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(total_room) as TotalRoomsMTD')->get();

        $TotalRoomsYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(total_room) as TotalRoomsYTD')->get();
        $TotalRoomsLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(total_room) as TotalRoomsLYSD')->get();

        $TotalRoomsLYY = DayCloseRecord::selectRaw('sum(total_room) as TotalRoomsLYY')
            ->whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->get();

        $TotalRoomLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(total_room) as TotalRoomLYMTD')->get();

        $Calculations['total_rooms'] = $TotalRooms->map(function ($q) use ($TotalRoomsMTD, $TotalRooms, $TotalRoomsYTD, $TotalRoomsLYSD, $TotalRoomsLYY, $TotalRoomLYMTD) {
            $q->TotalRoomsMTD = $TotalRoomsMTD[0]->TotalRoomsMTD;
            $q->TotalRoomsYTD = $TotalRoomsYTD[0]->TotalRoomsYTD;
            $q->TotalRoomsLYSD = $TotalRoomsLYSD[0]->TotalRoomsLYSD;
            $q->TotalRoomsLYY = $TotalRoomsLYY[0]->TotalRoomsLYY;
            $q->TotalRoomLYMTD = $TotalRoomLYMTD[0]->TotalRoomLYMTD;
            return $q;
        });


        $RoomsOccupied = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(oc_rooms)as RoomsOccupied')->get();

        $RoomsOccupiedMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(oc_rooms) as RoomsOccupiedMTD')->get();

        $RoomsOccupiedYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(oc_rooms) as RoomsOccupiedYTD')->get();

        $RoomsOccupiedLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(oc_rooms) as RoomsOccupiedLYSD')->get();

        $RoomsOccupiedLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(oc_rooms) as RoomsOccupiedLYY')
            ->get();
        $RoomsOccupiedLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(oc_rooms) as RoomsOccupiedLYMTD')->get();

        $Calculations['RoomsOccupied'] = $RoomsOccupied->map(function ($q) use ($RoomsOccupiedMTD, $RoomsOccupied, $RoomsOccupiedYTD, $RoomsOccupiedLYSD, $RoomsOccupiedLYY, $RoomsOccupiedLYMTD) {
            $q->RoomsOccupiedMTD = $RoomsOccupiedMTD[0]->RoomsOccupiedMTD;
            $q->RoomsOccupiedYTD = $RoomsOccupiedYTD[0]->RoomsOccupiedYTD;
            $q->RoomsOccupiedLYSD = $RoomsOccupiedLYSD[0]->RoomsOccupiedLYSD;
            $q->RoomsOccupiedLYY = $RoomsOccupiedLYY[0]->RoomsOccupiedLYY;
            $q->RoomsOccupiedLYMTD = $RoomsOccupiedLYMTD[0]->RoomsOccupiedLYMTD;
            return $q;
        });

        $RoomsOccupiedPercent = DayCloseRecord::whereDate('hotel_date', $request->date)->selectRaw('sum((oc_rooms/total_room)*100) as  RoomsOccupiedPercent')->get();

        $RoomsOccupiedPercentMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum((oc_rooms/total_room)*100) as  RoomsOccupiedPercentMTD')->get();

        $RoomsOccupiedPercentYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum((oc_rooms/total_room)*100) as  RoomsOccupiedPercentYTD')->get();

        $RoomsOccupiedPercentLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum((oc_rooms/total_room)*100) as  RoomsOccupiedPercentLYSD')->get();


        $RoomsOccupiedPercentLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum((oc_rooms/total_room)*100) as  RoomsOccupiedPercentLYY')->get();

        $RoomsOccupiedPercentLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum((oc_rooms/total_room)*100) as RoomsOccupiedPercentLYMTD')->get();

        $Calculations['RoomsOccupiedPercent'] = $RoomsOccupiedPercent->map(function ($q) use ($RoomsOccupiedPercentMTD, $RoomsOccupiedPercent, $RoomsOccupiedPercentYTD, $RoomsOccupiedPercentLYY, $RoomsOccupiedPercentLYSD, $RoomsOccupiedPercentLYMTD) {
            $q->RoomsOccupiedPercentMTD = $RoomsOccupiedPercentMTD[0]->RoomsOccupiedPercentMTD;
            $q->RoomsOccupiedPercentYTD = $RoomsOccupiedPercentYTD[0]->RoomsOccupiedPercentYTD;
            $q->RoomsOccupiedPercentLYY = $RoomsOccupiedPercentLYY[0]->RoomsOccupiedPercentLYY;
            $q->RoomsOccupiedPercentLYSD = $RoomsOccupiedPercentLYSD[0]->RoomsOccupiedPercentLYSD;
            $q->RoomsOccupiedPercentLYMTD = $RoomsOccupiedPercentLYMTD[0]->RoomsOccupiedPercentLYMTD;
            return $q;
        });

        $AvailableRooms = DayCloseRecord::whereDate('hotel_date', $request->date)->selectRaw('sum(total_room-oc_rooms) as AvailableRooms')->get();

        $AvailableRoomsMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(total_room-oc_rooms) as AvailableRoomsMTD')->get();

        $AvailableRoomsYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(total_room-oc_rooms) as AvailableRoomsYTD')->get();

        $AvailableRoomsLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(total_room-oc_rooms) as AvailableRoomsLYSD')->get();


        $AvailableRoomsLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(total_room-oc_rooms) as AvailableRoomsLYY')->get();

        $AvailableRoomsLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(total_room-oc_rooms) as AvailableRoomsLYMTD')->get();

        $Calculations['AvailableRooms'] = $AvailableRooms->map(function ($q) use ($AvailableRoomsMTD, $AvailableRooms, $AvailableRoomsYTD, $AvailableRoomsLYSD, $AvailableRoomsLYY, $AvailableRoomsLYMTD) {
            $q->AvailableRoomsMTD = $AvailableRoomsMTD[0]->AvailableRoomsMTD;
            $q->AvailableRoomsYTD = $AvailableRoomsYTD[0]->AvailableRoomsYTD;
            $q->AvailableRoomsLYSD = $AvailableRoomsLYSD[0]->AvailableRoomsLYSD;
            $q->AvailableRoomsLYY = $AvailableRoomsLYY[0]->AvailableRoomsLYY;
            $q->AvailableRoomsLYMTD = $AvailableRoomsLYMTD[0]->AvailableRoomsLYMTD;
            return $q;
        });
        $ComplimentaryRooms = DayCloseRecord::whereDate('hotel_date', $request->date)->selectRaw('sum(comp_rooms) as  ComplimentaryRooms')->get();

        $ComplimentaryRoomsMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(comp_rooms) as  ComplimentaryRoomsMTD')->get();

        $ComplimentaryRoomsYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(comp_rooms) as  ComplimentaryRoomsYTD')->get();

        $ComplimentaryRoomsLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(comp_rooms) as  ComplimentaryRoomsLYSD')->get();


        $ComplimentaryRoomsLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(comp_rooms) as  ComplimentaryRoomsLYY')->get();
        $ComplimentaryRoomsLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(comp_rooms) as ComplimentaryRoomsLYMTD')->get();

        $Calculations['ComplimentaryRooms'] = $ComplimentaryRooms->map(function ($q) use ($ComplimentaryRoomsMTD, $ComplimentaryRooms, $ComplimentaryRoomsYTD, $ComplimentaryRoomsLYSD, $ComplimentaryRoomsLYY, $ComplimentaryRoomsLYMTD) {
            $q->ComplimentaryRoomsMTD = $ComplimentaryRoomsMTD[0]->ComplimentaryRoomsMTD;
            $q->ComplimentaryRoomsYTD = $ComplimentaryRoomsYTD[0]->ComplimentaryRoomsYTD;
            $q->ComplimentaryRoomsLYSD = $ComplimentaryRoomsLYSD[0]->ComplimentaryRoomsLYSD;
            $q->ComplimentaryRoomsLYY = $ComplimentaryRoomsLYY[0]->ComplimentaryRoomsLYY;
            $q->ComplimentaryRoomsLYMTD = $ComplimentaryRoomsLYMTD[0]->ComplimentaryRoomsLYMTD;
            return $q;
        });

        $HouseUseRooms = DayCloseRecord::whereDate('hotel_date', $request->date)->selectRaw('(house_use_rooms) as  HouseUseRooms')->get();

        $HouseUseRoomsMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(house_use_rooms) as  HouseUseRoomsMTD')->get();

        $HouseUseRoomsYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('hotel_date,(house_use_rooms) as  HouseUseRoomsYTD')->get();

        $HouseUseRoomsLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(house_use_rooms) as  HouseUseRoomsLYSD')->get();

        $HouseUseRoomsLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(house_use_rooms) as  HouseUseRoomsLYY')->get();

        $HouseUseRoomsLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(house_use_rooms) as  HouseUseRoomsLYMTD')->get();

        $Calculations['HouseUseRooms'] = $HouseUseRooms->map(function ($q) use ($HouseUseRoomsMTD, $HouseUseRooms, $HouseUseRoomsYTD, $HouseUseRoomsLYSD, $HouseUseRoomsLYY, $HouseUseRoomsLYMTD) {
            $q->HouseUseRoomsMTD = $HouseUseRoomsMTD[0]->HouseUseRoomsMTD;
            $q->HouseUseRoomsYTD = $HouseUseRoomsYTD[0]->HouseUseRoomsYTD;
            $q->HouseUseRoomsLYSD = $HouseUseRoomsLYSD[0]->HouseUseRoomsLYSD;
            $q->ComplimentaryRoomsLYY = $HouseUseRoomsLYY[0]->HouseUseRoomsLYY;
            $q->HouseUseRoomsLYMTD = $HouseUseRoomsLYMTD[0]->HouseUseRoomsLYMTD;
            return $q;
        });

        $OutOfOrderRooms = DayCloseRecord::whereDate('hotel_date', $request->date)->selectRaw('sum(oo_rooms) as  OutOfOrderRooms')->get();

        $OutOfOrderRoomsMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(oo_rooms) as  OutOfOrderRoomsMTD')->get();

        $OutOfOrderRoomsYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(oo_rooms) as  OutOfOrderRoomsYTD')->get();

        $OutOfOrderRoomsLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(oo_rooms) as  OutOfOrderRoomsLYSD')->get();

        $OutOfOrderRoomsLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(oo_rooms) as  OutOfOrderRoomsLYY')->get();

        $OutOfOrderRoomsLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(oo_rooms) as  OutOfOrderRoomsLYMTD')->get();

        $Calculations['OutOfOrderRooms'] = $OutOfOrderRooms->map(function ($q) use ($OutOfOrderRoomsMTD, $OutOfOrderRoomsYTD, $OutOfOrderRoomsLYSD, $OutOfOrderRoomsLYY, $OutOfOrderRoomsLYMTD) {
            $q->OutOfOrderRoomsMTD = $OutOfOrderRoomsMTD[0]->OutOfOrderRoomsMTD;
            $q->OutOfOrderRoomsYTD = $OutOfOrderRoomsYTD[0]->OutOfOrderRoomsYTD;
            $q->OutOfOrderRoomsLYSD = $OutOfOrderRoomsLYSD[0]->OutOfOrderRoomsLYSD;
            $q->OutOfOrderRoomsLYY = $OutOfOrderRoomsLYY[0]->OutOfOrderRoomsLYY;
            $q->OutOfOrderRoomsLYMTD = $OutOfOrderRoomsLYMTD[0]->OutOfOrderRoomsLYMTD;
            return $q;
        });

        $TotalRoom_minusOOORooms = DayCloseRecord::whereDate('hotel_date', $request->date)->selectRaw('sum(total_room-oo_rooms) as  TotalRoom_minusOOORooms')->get();

        $TotalRoom_minus_OOORoomsMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(total_room-oo_rooms) as  TotalRoom_minus_OOORoomsMTD')->get();

        $TotalRoom_minus_OOORoomsYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(total_room-oo_rooms) as  TotalRoom_minus_OOORoomsYTD')->get();

        $TotalRoom_minus_OOORoomsLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())

            ->selectRaw('sum(total_room-oo_rooms) as  TotalRoom_minus_OOORoomsLYSD')->get();

        $TotalRoom_minus_OOORoomsLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(total_room-oo_rooms) as  TotalRoom_minus_OOORoomsLYY')->get();

        $TotalRoom_minus_OOORoomsLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(total_room-oo_rooms) as  TotalRoom_minus_OOORoomsLYMTD')->get();

        $Calculations['TotalRoom_minusOOORooms'] = $TotalRoom_minusOOORooms->map(function ($q) use ($TotalRoom_minus_OOORoomsMTD, $TotalRoom_minusOOORooms, $TotalRoom_minus_OOORoomsYTD, $TotalRoom_minus_OOORoomsLYSD, $TotalRoom_minus_OOORoomsLYY, $TotalRoom_minus_OOORoomsLYMTD) {
            $q->TotalRoom_minus_OOORoomsMTD = $TotalRoom_minus_OOORoomsMTD[0]->TotalRoom_minus_OOORoomsMTD;
            $q->TotalRoom_minus_OOORoomsYTD = $TotalRoom_minus_OOORoomsYTD[0]->TotalRoom_minus_OOORoomsYTD;
            $q->TotalRoom_minus_OOORoomsLYSD = $TotalRoom_minus_OOORoomsLYSD[0]->TotalRoom_minus_OOORoomsLYSD;
            $q->TotalRoom_minus_OOORoomsLYY = $TotalRoom_minus_OOORoomsLYY[0]->TotalRoom_minus_OOORoomsLYY;
            $q->TotalRoom_minus_OOORoomsLYMTD = $TotalRoom_minus_OOORoomsLYMTD[0]->TotalRoom_minus_OOORoomsLYMTD;
            return $q;
        });

        $Available_Room_minusOOORooms = DayCloseRecord::whereDate('hotel_date', $request->date)->selectRaw('sum((total_room-oc_rooms)-oo_rooms) as  Available_Room_minusOOORooms')->get();

        $Available_Room_minusOOORoomsMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum((total_room-oc_rooms)-oo_rooms) as  Available_Room_minusOOORoomsMTD')->get();

        $Available_Room_minusOOORoomsYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('hotel_date,((total_room-oc_rooms)-oo_rooms) as  Available_Room_minusOOORoomsYTD')->get();

        $Available_Room_minusOOORoomsLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())

            ->selectRaw('sum((total_room-oc_rooms)-oo_rooms) as  Available_Room_minusOOORoomsLYSD')->get();

        $Available_Room_minusOOORoomsLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum((total_room-oc_rooms)-oo_rooms ) as  Available_Room_minusOOORoomsLYY')->get();

        $Available_Room_minusOOORoomsLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum((total_room-oc_rooms)-oo_rooms) as  Available_Room_minusOOORoomsLYMTD')->get();

        $Calculations['Available_Room_minusOOORooms'] = $Available_Room_minusOOORooms->map(function ($q) use ($Available_Room_minusOOORoomsMTD, $Available_Room_minusOOORooms, $Available_Room_minusOOORoomsYTD, $Available_Room_minusOOORoomsLYSD, $Available_Room_minusOOORoomsLYY, $Available_Room_minusOOORoomsLYMTD) {
            $q->Available_Room_minusOOORoomsMTD = $Available_Room_minusOOORoomsMTD[0]->Available_Room_minusOOORoomsMTD;
            $q->Available_Room_minusOOORoomsYTD = $Available_Room_minusOOORoomsYTD[0]->Available_Room_minusOOORoomsYTD;
            $q->Available_Room_minusOOORoomsLYSD = $Available_Room_minusOOORoomsLYSD[0]->Available_Room_minusOOORoomsLYSD;
            $q->Available_Room_minusOOORoomsLYY = $Available_Room_minusOOORoomsLYY[0]->Available_Room_minusOOORoomsLYY;
            $q->Available_Room_minusOOORoomsLYMTD = $Available_Room_minusOOORoomsLYMTD[0]->Available_Room_minusOOORoomsLYMTD;
            return $q;
        });

        $RoomsOccupiedPercent_minus_OOORooms = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum((oc_rooms/total_room)*100 -(oo_rooms)) as RoomsOccupiedPercent_minus_OOORooms')->get();


        $RoomsOccupiedPercent_minus_OOORoomsMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum((oc_rooms/total_room)*100 -(oo_rooms))  as  RoomsOccupiedPercent_minus_OOORoomsMTD')->get();

        $RoomsOccupiedPercent_minus_OOORoomsYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum((oc_rooms/total_room)*100 -(oo_rooms))  as  RoomsOccupiedPercent_minus_OOORoomsYTD')->get();

        $RoomsOccupiedPercent_minus_OOORoomsLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum((oc_rooms/total_room)*100 -(oo_rooms)) as  RoomsOccupiedPercent_minus_OOORoomsLYSD')->get();

        $RoomsOccupiedPercent_minus_OOORoomsLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum((oc_rooms/total_room)*100 -(oo_rooms)) as  RoomsOccupiedPercent_minus_OOORoomsLYY')->get();

        $RoomsOccupiedPercent_minus_OOORoomsLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum((oc_rooms/total_room)*100 -(oo_rooms)) as  RoomsOccupiedPercent_minus_OOORoomsLYMTD')->get();


        $Calculations['RoomsOccupiedPercent_minus_OOORooms'] = $RoomsOccupiedPercent_minus_OOORooms->map(function ($q) use ($RoomsOccupiedPercent_minus_OOORoomsMTD, $RoomsOccupiedPercent_minus_OOORooms, $RoomsOccupiedPercent_minus_OOORoomsYTD, $RoomsOccupiedPercent_minus_OOORoomsLYSD, $RoomsOccupiedPercent_minus_OOORoomsLYY, $RoomsOccupiedPercent_minus_OOORoomsLYMTD) {
            $q->RoomsOccupiedPercent_minus_OOORoomsMTD = $RoomsOccupiedPercent_minus_OOORoomsMTD[0]->RoomsOccupiedPercent_minus_OOORoomsMTD;
            $q->RoomsOccupiedPercent_minus_OOORoomsYTD = $RoomsOccupiedPercent_minus_OOORoomsYTD[0]->RoomsOccupiedPercent_minus_OOORoomsYTD;
            $q->RoomsOccupiedPercent_minus_OOORoomsLYSD = $RoomsOccupiedPercent_minus_OOORoomsLYSD[0]->RoomsOccupiedPercent_minus_OOORoomsLYSD;
            $q->RoomsOccupiedPercent_minus_OOORoomsLYY = $RoomsOccupiedPercent_minus_OOORoomsLYY[0]->RoomsOccupiedPercent_minus_OOORoomsLYY;
            $q->RoomsOccupiedPercent_minus_OOORoomsLYMTD = $RoomsOccupiedPercent_minus_OOORoomsLYMTD[0]->RoomsOccupiedPercent_minus_OOORoomsLYMTD;
            return $q;
        });

        $AverageRoomRate = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('sum(total_rate_room/guest_inhouse_pax) as AverageRoomRate')->get();


        $AverageRoomRateMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(total_rate_room/guest_inhouse_pax)  as AverageRoomRateMTD')->get();


        $AverageRoomRateYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(total_rate_room/guest_inhouse_pax)   as AverageRoomRateYTD')->get();

        $AverageRoomRateLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(total_rate_room/guest_inhouse_pax) as  AverageRoomRateLYSD')->get();

        $AverageRoomRateLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(total_rate_room/guest_inhouse_pax) as AverageRoomRateLYY')->get();

        $AverageRoomRateLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(total_rate_room/guest_inhouse_pax) as AverageRoomRateLYMTD')->get();

        $Calculations['AverageRoomRate'] = $AverageRoomRate->map(function ($q) use ($AverageRoomRateMTD, $AverageRoomRate, $AverageRoomRateLYY, $AverageRoomRateYTD, $AverageRoomRateLYSD, $AverageRoomRateLYMTD) {
            $q->AverageRoomRateMTD = $AverageRoomRateMTD[0]->AverageRoomRateMTD;
            $q->AverageRoomRateYTD = $AverageRoomRateYTD[0]->AverageRoomRateYTD;
            $q->AverageRoomRateLYSD = $AverageRoomRateLYSD[0]->AverageRoomRateLYSD;
            $q->AverageRoomRateLYY = $AverageRoomRateLYY[0]->AverageRoomRateLYY;
            $q->AverageRoomRateLYMTD = $AverageRoomRateLYMTD[0]->AverageRoomRateLYMTD;
            return $q;
        });


        $AveragePersonRate = DayCloseRecord::whereDate('hotel_date', $request->date)
            ->selectRaw('(total_rate_room/oc_rooms) as AveragePersonRate')->get();


        $AveragePersonRateMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfMonth(), $request->date])
            ->selectRaw('sum(total_rate_room/oc_rooms)  as AveragePersonRateMTD')->get();

        $AveragePersonRateYTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->startOfYear(), $request->date])
            ->selectRaw('sum(total_rate_room/oc_rooms)  as AveragePersonRateYTD')->get();

        $AveragePersonRateLYSD = DayCloseRecord::whereDate('hotel_date', Carbon::parse($request->date)->subYear())
            ->selectRaw('sum(total_rate_room/oc_rooms) as  AveragePersonRateLYSD')->get();

        $AveragePersonRateLYY = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()->endOfYear()])
            ->selectRaw('sum(total_rate_room/oc_rooms) as AveragePersonRateLYY')->get();

        $AveragePersonRateLYMTD = DayCloseRecord::whereBetween('hotel_date', [Carbon::parse($request->date)->subYear()
            ->startOfYear(), Carbon::parse($request->date)->subYear()])
            ->selectRaw('sum(total_rate_room/oc_rooms) as AveragePersonRateLYMTD')->get();

        $Calculations['AveragePersonRate'] = $AveragePersonRate->map(function ($q) use ($AveragePersonRateMTD, $AveragePersonRate, $AveragePersonRateLYY, $AveragePersonRateYTD, $AveragePersonRateLYSD, $AveragePersonRateLYMTD) {
            $q->AveragePersonRateMTD = $AveragePersonRateMTD[0]->AveragePersonRateMTD;
            $q->AveragePersonRateYTD = $AveragePersonRateYTD[0]->AveragePersonRateYTD;
            $q->AveragePersonRateLYSD = $AveragePersonRateLYSD[0]->AveragePersonRateLYSD;
            $q->AveragePersonRateLYY = $AveragePersonRateLYY[0]->AveragePersonRateLYY;
            $q->AveragePersonRateLYMTD = $AveragePersonRateLYMTD[0]->AveragePersonRateLYMTD;
            return $q;
        });

        return response()->json([
            'data' => [
                'manager_report5'                => $manager_report5,
                'manager_report5_Calculations'   => $Calculations,
            ]

        ]);
    }


    //  public function Print_Invoice(Request $request)
    //  {
    //   $print_invoice=Guests::selectRaw('id ,in_date,out_date,no_of_nights,room_id,rm_rate ,no_of_pax,vat_no,checkout_at,created_inhousGuest_at,profile_id,company_id,room_type_id')
    //        ->with(['profile','room','company'])
    //         ->where('id',$request->guest_id)

    //         ->with(['window' =>function($q) use($request){
    //             $q->with(['transactions' =>function($q1){
    //                  $q1->selectRaw('hotel_date,room_id,description,charge_amount,payment_amount');

    //                 }]);
    //                 $q->where('id',$request->window_id);
    //             }])
    //   ->get();
    //   $tenant = Tenant::current();

    //   $TaxInvoice = cache()->get('settings_'.$tenant->id)->first();
    //   $filteredTaxInvoice = $TaxInvoice->only(['name','name_loc,','phone','email','address','city','vat_no']);

    //   return response()->json(['data'=>[
    //     'TaxInvoice'     =>$filteredTaxInvoice,
    //     'print_invoice'  =>$print_invoice
    //   ]
    // ]);


    //  }

    public function Meal_Plan(Request $request)
    {
        $guestInfo = [];

        $Guest_InHouse_with_MealPlan = Guests::with('profile', 'room', 'meal_package.meal')

            ->whereDate('in_date', ' <=', $request->startDate)
            ->whereDate('out_date', '>=', $request->startDate)
            ->get();

        foreach ($Guest_InHouse_with_MealPlan as $guest) {
            $data = $this->Meals($guest);
            // return $guest->first();
            $rr = collect([$guest])->map(function ($q) use ($data) {
                $q->Guest_Pax = $data;
                return $q;
            });

            array_push($guestInfo, $rr[0]);
        }

        return response()->json([
            'Guest_InHouse_with_MealPlan' => $guestInfo,

        ]);
    }

    public function Meals($guest)
    {
        $arr = [];
        $arr2 = [];
        //dd($this->meal->meal_type);


        if (!empty($guest->meal->meal_type)) {
            if ($guest->meal->meal_type == 'BF') {

                $arr = [
                    'BF' => $guest->no_of_pax
                ];
            } elseif ($guest->meal->meal_type == 'Ln') {

                $arr = [
                    'Ln' => $guest->no_of_pax
                ];
            } elseif ($guest->meal->meal_type == 'Di') {
                $arr = [
                    'Di' => $guest->no_of_pax
                ];
            } elseif ($guest->meal->meal_type == 'IF') {
                $arr = [
                    'IF' => $guest->no_of_pax
                ];
            } elseif ($guest->meal->meal_type == 'Sr') {

                $arr = [
                    'Sr' => $guest->no_of_pax
                ];
            } elseif ($guest->meal->meal_type == 'Ot') {

                $arr = [
                    'Ot' => $guest->no_of_pax
                ];
            }
            foreach ($guest->meal_package->meal as $package) {
                // return($guest->meal_package->meal);
                if ($package->meal_type == 'Ln') {

                    $arr2 = [
                        'Ln' => $guest->no_of_pax
                    ];
                } elseif ($package->meal_type == 'BF') {


                    $arr2 = [
                        'BF' => $guest->no_of_pax
                    ];
                } elseif ($package->meal_type == 'Di') {

                    $arr2 = [
                        'Di' => $guest->no_of_pax
                    ];
                } elseif ($package->meal_type == 'Sr') {


                    $arr2 = [
                        'Sr' => $guest->no_of_pax
                    ];
                } elseif ($package->meal_type == 'IF') {

                    $arr2 = [
                        'IF' => $guest->no_of_pax
                    ];
                } elseif ($package->meal_type == 'Ot') {

                    $arr2 = [
                        'Ot' => $guest->no_of_pax
                    ];
                }
            }
            $data = [
                'meal_type' => $arr,
                'package_mealType' => $arr2,
            ];
        }
        return $data;
    }


    private function sales_in_future($dateLoop, $cat_id)
    {
        $sales_in_future = preCharge::whereDate('hotel_date', $dateLoop)
            ->join('ledgers', 'pre_charges.ledger_id', 'ledgers.id')

            ->where('ledgers.ledger_cat_id', $cat_id)
            ->sum('amount');
        return $sales_in_future;
    }

    public function History_And_Forecast(Request $request)
    {
        $sales_arr = [];
        $roomRev = 0;
        $FB = 0;
        $Com = 0;
        $Lun = 0;
        $RoomPercentage = 0;
        $Rooms = 0;
        $OoRooms = 0;
        $totRoom = 0;


        $currentDate = Carbon::now();
        $start = Carbon::parse($request->startDate);
        $end = Carbon::parse($request->endDate);

        for ($formDate = $start; $formDate->lte($end); $formDate->addDay()) {

            if ($formDate < $currentDate) {

                $roomRev = $this->revenueByCatByDate($formDate, 1);
                $FB = $this->revenueByCatByDate($formDate, 2);
                $Com = $this->revenueByCatByDate($formDate, 3);
                $Lun = $this->revenueByCatByDate($formDate, 4);
                $other = $this->revenueByCatByDate($formDate, 5);
                $total = $roomRev + $FB + $Com + $Lun + $other;


                $totRoom = DayCloseRecord::whereDate('hotel_date', '=', $formDate)
                    ->sum('total_room');
                if ($totRoom == 0) {
                    $totRoom = 1;
                }
                $RateRoom = $roomRev / $totRoom;

                if ($totRoom != 0) {
                    $RoomPercentage = ($Rooms / $totRoom) * 100;
                } else {
                    $RoomPercentage = 0;
                }
                $OoRooms = DayCloseRecord::whereDate('hotel_date', '=', $formDate)
                    ->sum('oo_rooms');

                $Rooms = DayCloseRecord::whereDate('hotel_date', '=', $formDate)
                    ->sum('oc_rooms');
                if ($Rooms != 0) {


                    $RoomPercentage = ($Rooms / $totRoom) * 100;
                } else {
                    $RoomPercentage = 0;
                }

                $Pax = DayCloseRecord::whereDate('hotel_date', '=', $formDate)
                    ->sum('guest_inhouse_pax');

                if ($Pax == 0) {
                    $Pax = 1;
                }

                $RatePax = $roomRev / $Pax;

                $date = $formDate->format('Y-m-d');

                $count_Past_data = [
                    'date' => $date,
                    'Acco' => $roomRev,
                    'fb' => $FB,
                    'com' => $Com,
                    'lun' => $Lun,
                    'other' => $other,
                    'total' => $total,
                    'Total_Rooms' => $totRoom,
                    'oc_rooms' => $Rooms,
                    'OoRooms' => $OoRooms,
                    'Pax' => $Pax,
                    'rateRoom' => $RateRoom,
                    'ratePax' => $RatePax,
                    'RoomPercentage' => $RoomPercentage,

                ];
                array_push($sales_arr, $count_Past_data);
            } else {

                $roomRev = $this->sales_in_future($formDate, 1);
                $FB = $this->sales_in_future($formDate, 2);
                $Com = $this->sales_in_future($formDate, 3);
                $Lun = $this->sales_in_future($formDate, 4);
                $other = $this->sales_in_future($formDate, 5);
                $total = $roomRev + $FB + $Com + $Lun + $other;




                $totRoom = Guests::whereDate('in_date', '<=', $formDate)
                    ->whereDate('out_date', '>=', $formDate)
                    ->where('is_checked_in', 1)
                    ->where('checked_out', 0)
                    ->orWhere('is_reservation', 1)
                    ->whereDate('in_date', '<=', $formDate)
                    ->whereDate('out_date', '>=', $formDate)
                    ->where('is_cancel', 0)->count();

                if ($totRoom != 0) {
                    $RoomPercentage = ($Rooms / $totRoom) * 100;
                } else {
                    $RoomPercentage = 0;
                }



                if ($totRoom == 0) {
                    $totRoom = 1;
                }
                $RateRoom = $roomRev / $totRoom;

                $Pax = Guests::whereDate('in_date', '<=', $formDate)
                    ->whereDate('out_date', '>=', $formDate)
                    ->where('is_checked_in', 1)
                    ->where('checked_out', 0)
                    ->orWhere('is_reservation', 1)
                    ->whereDate('in_date', '<=', $formDate)
                    ->whereDate('out_date', '>=', $formDate)
                    ->where('is_cancel', 0)->sum('no_of_pax');
                if ($Pax == 0) {
                    $Pax = 1;
                }
                $RatePax = $roomRev / $Pax;

                $outOfOrder_Future = OordService::whereDate('start_date', '<=', $formDate)
                    ->whereDate('end_date', '>=', $formDate)
                    ->where('oord_type', 'OO')
                    ->count();

                $Rooms = Guests::whereDate('in_date', '<=', $formDate)

                    ->whereDate('out_date', '>=', $formDate)
                    ->where('is_checked_in', 1)
                    ->where('checked_out', 0)
                    ->orWhere('is_reservation', 1)
                    ->whereDate('in_date', '<=', $formDate)
                    ->whereDate('out_date', '>=', $formDate)
                    ->where('is_cancel', 0)->count('is_checked_in');
                if ($Rooms != 0) {

                    $RoomPercentage = ($Rooms / $totRoom) * 100;
                } else {
                    $RoomPercentage = 0;
                }

                $date = $formDate->format('Y-m-d');
                $count_future_data = [
                    'date' => $date,
                    'Acco' => $roomRev,
                    'fb' => $FB,
                    'com' => $Com,
                    'lun' => $Lun,
                    'other' => $other,
                    'total' => $total,
                    'Total_Rooms' => $totRoom,
                    'oc_rooms' => $Rooms,
                    'OoRooms' => $outOfOrder_Future,
                    'pax' => $Pax,
                    'rateRoom' => $RateRoom,
                    'ratePax' => $RatePax,
                    'RoomPercentage' => $RoomPercentage,


                ];

                array_push($sales_arr, $count_future_data);
            }
        }

        return response()->json(['History_And_Forecast' => $sales_arr]);
    }


    public function Monthwise_Revenue(Request $request)
    {
        $year = $request->year;



        $MonthWise_revenue = LedgerCat::with(['dayCloseSalesLedCat' => function ($q) use ($year) {
            $q->whereYear('hotel_date', $year);
            $q->selectRaw('sum(charge_amount) as   amount, ledger_cat_id')->groupBy('ledger_cat_id');
            $q->selectRaw('DATE_FORMAT(hotel_date, "%m") as Month')
                ->groupBy('Month');
        }])
            ->get();

        return response()->json(['MonthWise_revenue' => $MonthWise_revenue]);
    }


    public function Monthwise_Occupancy(Request $request)
    {

        $year = $request->year;
        $startDate = Carbon::createFromFormat('Y-m-d', $year . '-01-01');
        $endDate = Carbon::createFromFormat('Y-m-d', $year . '-12-31');

        for ($monthDate = $startDate; $monthDate->lte($endDate); $monthDate->addMonth()) {
            // $monthName = $monthDate->format('F');

            $MonthwiseOccupancy = DayCloseRecord::whereYear('hotel_date', $year)
                ->selectRaw('sum(oc_rooms/total_room)*100 as OccupancyPercentage')
                ->selectRaw('DATE_FORMAT(hotel_date, "%m") as Month')
                ->groupBy('Month')
                ->get();
        }

        return response()->json(['MonthwiseOccupancy' => $MonthwiseOccupancy]);
    }


    public function Daywise_Occupancy(Request $request)
    {

        $dayWise_occupancy = DayCloseRecord::selectRaw('sum(oc_rooms/total_room)*100 as DayWiseOccPercent')
            ->whereMonth('hotel_date', $request->month)
            ->whereYear('hotel_date', $request->year)
            ->selectRaw('DATE_FORMAT(hotel_date, "%d") as Day')->groupBy('Day')
            ->get();
        return response()->json(['DayWise_Occupancy' => $dayWise_occupancy]);
    }

    public function  Live_Occupancy_Calculations(Request $request)
    {
        $total_rooms = Room::where('rm_max_pax', '>', 0)->where('rm_active', 1)->count();

        $total_unavailable_rooms = OordService::whereDate('start_date', '<=', $request->hotel_date)
            ->whereDate('end_date', '>=', $request->hotel_date)
            ->where('is_return', 0)->count();

        $total_sellable_rooms = $total_rooms - $total_unavailable_rooms;

        $occupied_rooms = Room::where('fo_status', 'OC')->count();
        $vacant_rooms = Room::where('fo_status', 'VA')->count();

        $expected_arrivals = Guests::whereDate('in_date', $request->hotel_date)
            ->where('is_reservation', 1)
            ->where('is_cancel', 0)->count();

        $Due_out = Guests::whereDate('out_date', $request->hotel_date)
            ->where('is_checked_in', 1)
            ->where('checked_out', 0)->count();


        $tot_rm_rate = Guests::whereDate('in_date', '<=', $request->hotel_date)
            ->whereDate('out_date', '>=', $request->hotel_date)
            ->where('is_checked_in', 1)
            ->where('checked_out', 0)->sum('rm_rate');

        $avg_room_rate = $tot_rm_rate / $occupied_rooms;

        $tot_pax = Guests::whereDate('in_date', '<=', $request->hotel_date)
            ->whereDate('out_date', '>=', $request->hotel_date)
            ->where('is_checked_in', 1)
            ->where('checked_out', 0)->count('no_of_pax');

        $requestDate = Carbon::parse($request->hotel_date);
        $lastDay = $requestDate->subDay();
        $occupied_room_last = DayCloseRecord::where('hotel_date', $lastDay)->sum('oc_rooms');


        $arrived_rooms = Guests::whereDate('in_date', $request->hotel_date)
            ->where('is_checked_in', 1)
            ->where('checked_out', 0)->count();


        $checkedOut_rooms = Guests::whereDate('out_date', $request->hotel_date)
            ->where('checked_out', 1)->count();

        $expected_departures = Guests::whereDate('out_date', $request->hotel_date)
            ->where('is_checked_in', 1)->where('checked_out', 0)->count();


        $expected_occupied_tonight = Guests::whereDate('in_date', '<=', $request->hotel_date)
            ->whereDate('out_date', '>=', $request->hotel_date)

            ->where('is_cancel', 0)->count();

        $expected_occupancy = ($expected_occupied_tonight /  $total_rooms) * 100;

        $Live_Occupancy = [

            'Total_rooms'               => $total_rooms,
            'Total_unavailable_rooms'   => $total_unavailable_rooms,
            'Total_sellable_rooms'      => $total_sellable_rooms,
            'Occupied_rooms'            => $occupied_rooms,
            'Vacant_rooms'              => $vacant_rooms,
            'Expected_arrivals'         => $expected_arrivals,
            'Due_out'                   => $Due_out,
            'Tot_rm_rate'               => $tot_rm_rate,
            'Avg_room_rate'             => $avg_room_rate,
            'Tot_pax'                   => $tot_pax,
            'Occupied_room_last'        => $occupied_room_last,
            'Arrived_rooms'             => $arrived_rooms,
            'CheckedOut_rooms'          => $checkedOut_rooms,
            'Expected_departures'       => $expected_departures,
            'Expected_occupied_tonight' => $expected_occupied_tonight,
            'Expected_occupancy'        => $expected_occupancy,
        ];

        return response()->json(['Live_Occupancy' => $Live_Occupancy]);
    }

    public function Find_Folio(Request $request)
    {

        if ($request->rm_name != null) {
            $rooms = Room::with(['guests' => function ($q) {
                $q->where('is_checked_in', 1)->where('checked_out', 0);
                $q->with('profile');
            }])->when($request->has('rm_name'), function ($query) use ($request) {
                return $query->where('rm_name_en', $request->rm_name);
                return $query->orWhere('rm_name_loc', $request->rm_name);
            })
                ->first();
            $rm_name = $rooms->rm_name_en;
            $rm_name_loc = $rooms->rm_name_loc;
            $guest_id = $rooms->guests->id;
            $profileFirstName = $rooms->guests->profile->first_name;
            $profileLastName = $rooms->guests->profile->last_name;
            $Room = [
                'rm_name' => $rm_name,
                'rm_name_loc' => $rm_name_loc,
                'guest_id' => $guest_id,
                'profileFirstName' => $profileFirstName,
                'profileLastName' => $profileLastName,
            ];
            return response()->json(['Room' => $Room]);
        } else {
            $guest = Guests::with('profile', 'room')
                ->where('is_checked_in', 1)->where('checked_out', 0)
                ->when($request->has('guest_id'), function ($query1) use ($request) {
                    return $query1->where('id', $request->guest_id);
                })
                ->first();


            $guest_id = $guest->id;
            $profileFirstName = $guest->profile->first_name;
            $profileLastName = $guest->profile->last_name;
            $rm_name = $guest->room->rm_name_en;
            $rm_name_loc = $guest->room->rm_name_loc;
            $Guest = [

                'guest_id' => $guest_id,
                'profileFirstName' => $profileFirstName,
                'profileLastName' => $profileLastName,
                'rm_name'         => $rm_name,
                'rm_name_loc' => $rm_name_loc,
            ];

            return response()->json(['Guest' => $Guest]);
        }
        return response()->json("notFound");
    }


    public function  Transaction_Printing(Request $request)
    {
        $transactionPrinting = Transaction::with(['ledger', 'created_by', 'guest.profile', 'room', 'roomType'])
            ->where('id', $request->transaction_id)
            ->get();
        $tenant = Tenant::current();

        $PrintTransaction = cache()->get('settings_' . $tenant->id)->first();
        $filteredPrintTransaction = $PrintTransaction->only(['name', 'name_loc,', 'phone', 'email', 'address', 'city', 'vat_no']);

        return response()->json([
            'data' => [

                'transactionPrinting' => $transactionPrinting,
                'PrintTransaction' => $filteredPrintTransaction,
            ]
        ]);
    }

    public function  Voucher_Printing(Request $request)
    {
        $voucherPrinting = Transaction::with(['payment_type', 'created_by', 'guest.profile', 'room', 'roomType'])
            ->where('id', $request->transaction_id)
            ->get();

        $tenant = Tenant::current();

        $PrintVoucher = cache()->get('settings_' . $tenant->id)->first();
        $filteredPrintVoucher = $PrintVoucher->only(['name', 'name_loc,', 'phone', 'email', 'address', 'city', 'vat_no']);

        return response()->json([
            'data' => [

                'voucherPrinting' => $voucherPrinting,
                'PrintVoucher' => $filteredPrintVoucher,
            ]
        ]);
    }

    public function Room_Guests_History(Request $request)
    {
        $room_guestHistory = Guests::with('room', 'profile')->where('room_id', $request->room_id)
            ->where('checked_out', 1)->where('is_reservation', 0)
            ->where('is_checked_in', 0)
            ->get();
        return response()->json(['room_guestHistory' => $room_guestHistory]);
    }
    ////////////////////////// not report///////////////////////////////////////////
    public function recheckIn(Request $request)
    {

        $roomStatusController = new RoomStatusHistoryController();

        $guestUpdate = Guests::where('id', $request->guest_id)->update([
            'checked_out'   => 0,
            'is_checked_in' => 1,
        ]);
        $guestData = Guests::where('id', $request->guest_id)->first('room_id');

        $room = Room::where('id', $guestData->room_id)->first();
        $roomStatusController->InsertStatusChange($guestData->room_id, 'change By recheckIn', $room->fo_status, 'OC', $room->hk_stauts, 'DI');
        $room->update([
            'fo_status' => 'OC',
            'hk_stauts' => 'DI'
        ]);

        return response()->json(['message' => 'Everything Is Updated']);
    }

    public function Accounts_Statement(Request $request)
    {

        $account =  ChartOfAccounts::where('id', $request->account_id)->get();
        $account_code = trim($account[0]->account_code);
        $ret = ChartOfAccounts::where('account_code', 'like', $account_code . '%')->get(['id']);
        $dat = [];
        foreach ($ret as $id) {
            array_push($dat, $id->id);
        };

        $account_state = JournalVoucherMaster::whereBetween('jv_date', [$request->startDate, $request->endDate])
            ->with(['journalVoucherDetails' => function ($q) use ($request, $dat) {
                $q->whereIn('account_id', $dat);
            }])
            ->whereHas('journalVoucherDetails', function ($q1) use ($request, $dat) {
                $q1->whereIn('account_id', $dat);
            })
            ->get();
        return response()->json(['Account_statements' => $account_state]);
    }

    public function Accounts_Trail_Balance(Request $request)

    {
        $chart_of_account = ChartOfAccount::with('child.child.child.child.child')
            ->where('main_account_id', null)->get();
        return $chart_of_account;

        $childIDs = [];
        $start = Carbon::parse($request->startDate);
        $end = Carbon::parse($request->endDate);
        $formattedDateStart = $start->format('Y-m-d H:i:s');

        $formattedDateEnd = $end->format('Y-m-d H:i:s');
        // dd($formattedDateEnd);
        foreach ($chart_of_account as $accountChart) {

            array_push($childIDs, [$accountChart->id =>
            $accountChart->BalanceWithChilds($formattedDateStart, $formattedDateEnd)]);
        };
        return $childIDs;
    }



    public function ShowInvoice($uuid)
    {
        $ShowInvoice = Invoice::where('uuid', $uuid)->with(['guest.profile', 'guest.company', 'guest.room', 'window', 'user'])->get();
        return response()->json(['Public-Copy-Invoice' => $ShowInvoice]);
    }

    //////////////////////////////////////////////////////////////////////////////////

    public function SetUp_DayCloseRecord(Request $request)
    {

        $IdsCollection = $request->ids;
        foreach ($IdsCollection as $id) {

            $dayClose = Reports::where('id', $id)->update([

                'is_day-close' => 1,
            ]);
        }
        dd('Done');
    }

    public function Get_Reports()
    {
        $get_reports = Reports::get();

        return response()->json(['All_Reports' => $get_reports]);
    }

    public function Get_Reports_Is_Day_Close()
    {
        $reports_is_dayClose = Reports::where('is_day-close', 1)->get();

        return response()->json(['Day_Close_Record_Reports' => $reports_is_dayClose]);
    }

    public function Print_MultiReports_in_one_pdf(Request $request)
    {

        $dompdf = new Dompdf();

        $reports = Reports::where('is_day-close', 1)->get();

        $pdfContent = '';
        foreach ($reports as $report1) {

            //  return $report;
            $pdfContent .= '<h1>' . $report1['method'] . '</h1>';
            $pdfContent .= '<p>' .  $report1['name'] . '</p>';
            $pdfContent .= '<p>' .  $report1['name_loc'] . '</p>';
            $pdfContent .= '<p>' .  $report1['is_day_close'] . '</p>';
            $pdfContent .= '<p>' .  $report1['is_hijri'] . '</p>';
            $pdfContent .= '<p>' .  $report1['is_single-date'] . '</p>';
            $pdfContent .= '<p>' .  $report1['is_range-date'] . '</p>';
            $pdfContent .= '<hr>';
        }

        //        $pdfContent = '';
        //        foreach ($reports as $report2) {

        //            $pdfContent .= '<h1>' . $report2['method'] . '</h1>';
        //            $pdfContent .= '<p>' .  $report2['name'] . '</p>';
        //            $pdfContent .= '<p>' .  $report2['name_loc'] . '</p>';
        //            $pdfContent .= '<p>' .  $report2['is_day_close'] . '</p>';
        //            $pdfContent .= '<p>' .  $report2['is_hijri'] . '</p>';
        //            $pdfContent .= '<p>' .  $report2['is_single-date'] . '</p>';
        //            $pdfContent .= '<p>' .  $report2['is_range-date'] . '</p>';
        //            $pdfContent .= '<hr>'; 
        //     }
        //     $pdfContent = '';
        //     foreach ($reports as $report3) {

        //            $pdfContent .= '<h1>' . $report3['method'] . '</h1>';
        //            $pdfContent .= '<p>' .  $report3['name'] . '</p>';
        //            $pdfContent .= '<p>' .  $report3['name_loc'] . '</p>';
        //            $pdfContent .= '<p>' .  $report3['is_day_close'] . '</p>';
        //            $pdfContent .= '<p>' .  $report3['is_hijri'] . '</p>';
        //            $pdfContent .= '<p>' .  $report3['is_single-date'] . '</p>';
        //            $pdfContent .= '<p>' .  $report3['is_range-date'] . '</p>';
        //            $pdfContent .= '<hr>'; 
        // }
        // $pdfContent = '';
        // foreach ($reports as $report4) {

        //            $pdfContent .= '<h1>' . $report4['method'] . '</h1>';
        //            $pdfContent .= '<p>' .  $report4['name'] . '</p>';
        //            $pdfContent .= '<p>' .  $report4['name_loc'] . '</p>';
        //            $pdfContent .= '<p>' .  $report4['is_day_close'] . '</p>';
        //            $pdfContent .= '<p>' .  $report4['is_hijri'] . '</p>';
        //            $pdfContent .= '<p>' .  $report4['is_single-date'] . '</p>';
        //            $pdfContent .= '<p>' .  $report4['is_range-date'] . '</p>';
        //            $pdfContent .= '<hr>'; 
        //        }

        $dompdf->loadHtml($pdfContent);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('multi_report.pdf');
    }


    public function show($id)

    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**           
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        //
    }
}
