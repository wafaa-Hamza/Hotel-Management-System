<?php

namespace Database\Seeders;

use App\Models\Reports;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ReportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reports::Create([

            'name'              =>'total_closer_payment',
            'name_loc'          =>'total_closer_payment-loc',
            'report_section'    =>'accounts',
            'method'            =>'tot_payment_of_cashier_closer',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);

        Reports::Create([

            'name'              =>'total_summary_payment',
            'name_loc'          =>'total_summary_payment-loc',
            'report_section'    =>'accounts',
            'method'            =>'tot_payment_of_cashier_summary',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);

        Reports::Create([

            'name'              =>'Productivity-company',
            'name_loc'          =>'Productivity-company-loc',
            'report_section'    =>'calculation-amount-company',
            'method'            =>'ProductivityByCompany',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'productivity-country',
            'name_loc'          =>'Productivity-country-loc',
            'report_section'    =>'calculation-amount-country',
            'method'            =>'ProductivityByCountry',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);

        Reports::Create([

            'name'              =>'productivity-source-business',
            'name_loc'          =>'Productivity-source-business-loc',
            'report_section'    =>'calculation-amount-source-business',
            'method'            =>'ProductivityBySource_Business',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'productivity-market-segment',
            'name_loc'          =>'Productivity-market-segment-loc',
            'report_section'    =>'calculation-amount-market-segment',
            'method'            =>'ProductivityByMarket_Segments',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'guest_ledger',
            'name_loc'          =>'guest_ledger-loc',
            'report_section'    =>'sum-charge-amount',
            'method'            =>'Guest_Ledger_Report',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'voided-transaction',
            'name_loc'          =>'voided-transaction-loc',
            'report_section'    =>'get-and-sum-data',
            'method'            =>'Voided_Transactions',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'transaction-details',
            'name_loc'          =>'transaction-details-loc',
            'report_section'    =>'get-and-sum-data',
            'method'            =>'Transactions_Details',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'transactions_details_by_user',
            'name_loc'          =>'transactions_details_by_user-loc',
            'report_section'    =>'accounts',
            'method'            =>'Transactions_Details_By_User',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);

        Reports::Create([

            'name'              =>'inHouse-guest',
            'name_loc'          =>'inHouse-guest-loc',
            'report_section'    =>'managers',
            'method'            =>'InHouse_Guests_Report',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'room-change',
            'name_loc'          =>'room-change-loc',
            'report_section'    =>'managers',
            'method'            =>'Room_Change_Report',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'rate-change',
            'name_loc'          =>'room-change-loc',
            'report_section'    =>'managers',
            'method'            =>'Rate_Change_Report',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);

        Reports::Create([

            'name'              =>'act-checkin',
            'name_loc'          =>'act-checkin-loc',
            'report_section'    =>'reservation',
            'method'            =>'Actual_CheckedIn_And_Arrivals',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'act-checkout',
            'name_loc'          =>'act-checkout-loc',
            'report_section'    =>'reservation',
            'method'            =>'Checked_Out_And_Departure_Report',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'revenue',
            'name_loc'          =>'revenue-loc',
            'report_section'    =>'managers',
            'method'            =>'Revenue_By_Dates_Report',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'receipt-voucher',
            'name_loc'          =>'receipt-voucher-loc',
            'report_section'    =>'managers',
            'method'            =>'Receipt_Voucher_List_Report',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'payment-voucher',
            'name_loc'          =>'payment-voucher-loc',
            'report_section'    =>'managers',
            'method'            =>'Payment_Voucher_List_Report',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'room-status',
            'name_loc'          =>'room-status-loc',
            'report_section'    =>'house-keeping',
            'method'            =>'Rooms_Status_Report',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'oo and os',
            'name_loc'          =>'oo and os-loc',
            'report_section'    =>'reception',
            'method'            =>'OO_And_OS_Report',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'reservation',
            'name_loc'          =>'reservation-loc',
            'report_section'    =>'reservation',
            'method'            =>'Reservation_Report',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'reservation-amount',
            'name_loc'          =>'reservation-amount-loc',
            'report_section'    =>'reservation',
            'method'            =>'Res_Amount',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'income-statement',
            'name_loc'          =>'income-statement-loc',
            'report_section'    =>'managers',
            'method'            =>'Income_Statement_Report',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'income-statement-mytd',
            'name_loc'          =>'income-statement-mytd-loc',
            'report_section'    =>'managers',
            'method'            =>'Income_Statement_Report_MTD_YTD',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'balance',
            'name_loc'          =>'balance-loc',
            'report_section'    =>'managers',
            'method'            =>'High_Balance_Report',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'occupancy-by-mo',
            'name_loc'          =>'occupancy-by-mo-loc',
            'report_section'    =>'accounts',
            'method'            =>'Occupancy_By_Month',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'occupancy-by-mtd',
            'name_loc'          =>'occupancy-by-mtd-loc',
            'report_section'    =>'accounts',
            'method'            =>'Occupancy_By_MTD',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'house-keeping',
            'name_loc'          =>'house-keeping-loc',
            'report_section'    =>'house-keeping',
            'method'            =>'House_Keeping_By_Status',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'daily-sales',
            'name_loc'          =>'daily-sales-loc',
            'report_section'    =>'reception',
            'method'            =>'Daily_Sales_Report',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'reservation-by-company',
            'name_loc'          =>'reservation-by-company-loc',
            'report_section'    =>'reservation',
            'method'            =>'Reservation_By_Company',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'voucher',
            'name_loc'          =>'voucher-loc',
            'report_section'    =>'accounts',
            'method'            =>'Voucher_List',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'transaction-list',
            'name_loc'          =>'transaction-list-loc',
            'report_section'    =>'managers',
            'method'            =>'Transactions_List_View',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'manager1',
            'name_loc'          =>'manager1-loc',
            'report_section'    =>'manager',
            'method'            =>'Manager1_Report',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'manager5',
            'name_loc'          =>'manager5-loc',
            'report_section'    =>'manager',
            'method'            =>'Manager5_Report',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'invoice',
            'name_loc'          =>'invoice-loc',
            'report_section'    =>'reception',
            'method'            =>'Print_Invoice',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'meal',
            'name_loc'          =>'meal-loc',
            'report_section'    =>'manager',
            'method'            =>'Meal_Plan',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'history-and-forecast',
            'name_loc'          =>'history-and-forecast-loc',
            'report_section'    =>'manager',
            'method'            =>'History_And_Forecast',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'month-wise-revenue',
            'name_loc'          =>'month-wise-revenue-loc',
            'report_section'    =>'reservation',
            'method'            =>'Monthwise_Revenue',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'month-wise-occupancy',
            'name_loc'          =>'month-wise-occupancy-loc',
            'report_section'    =>'reservation',
            'method'            =>'Monthwise_Occupancy',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'day-wise-occupancy',
            'name_loc'          =>'day-wise-occupancy-loc',
            'report_section'    =>'reservation',
            'method'            =>'Daywise_Occupancy',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'live-occupancy',
            'name_loc'          =>'live-occupancy-loc',
            'report_section'    =>'manager',
            'method'            =>'Live_Occupancy_Calculations',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'find-folio',
            'name_loc'          =>'find-folio-loc',
            'report_section'    =>'reservation',
            'method'            =>'Find_Folio',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'transaction-print',
            'name_loc'          =>'transaction-print-loc',
            'report_section'    =>'reception',
            'method'            =>'Transaction_Printing',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'voucher-print',
            'name_loc'          =>'voucher-print-loc',
            'report_section'    =>'reception',
            'method'            =>'Voucher_Printing',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);
        Reports::Create([

            'name'              =>'room-guest',
            'name_loc'          =>'room-guest-loc',
            'report_section'    =>'reservation',
            'method'            =>'Room_Guests_History',
            'is_day-close'      =>0,
            'is_hijri'          =>0,
            'is_single-date'    =>1,
            'is_range-date'     =>1,
        ]);

    }
}
