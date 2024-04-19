<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class permissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        config()->set('auth.defaults.guard', 'web');
        $permissions = [
            // User permissions

            ['name' => 'view-user'],
            ['name' => 'create-user'],
            ['name' => 'edit-user'],
            ['name' => 'delete-user'],
            //

            //budget

            ['name' => 'view-budget'],
            ['name' => 'create-budget'],
            ['name' => 'edit-budget'],
            ['name' => 'delete-budget'],

            //day close seeder


            //day colse record--

            ['name' => 'create-daycloserecord'],

            //journal voucher--

            ['name' => 'view-journal voucher'],
            ['name' => 'create-journal voucher'],
            ['name' => 'edit-journal voucher'],
            ['name' => 'delete-journal voucher'],

            //journal voucher type--

            ['name' => 'view-journal voucher type'],
            ['name' => 'create-journal voucher type'],
            ['name' => 'edit-journal voucher type'],
            ['name' => 'delete-journal voucher type'],


            //precharge--

            ['name' => 'create-precharge'],

            //charge routed--

            ['name' => 'view-chargerouted'],
            ['name' => 'create-chargerouted'],
            ['name' => 'edit-chargerouted'],
            ['name' => 'delete-chargerouted'],


            // Chart of account permissions

            ['name' => 'view-chart of account'],
            ['name' => 'create-chart of account'],
            ['name' => 'edit-chart of account'],
            ['name' => 'delete-chart of account'],

            //discrepancy--

            ['name' => 'view-discrepancy'],
            ['name' => 'create-discrepancy'],
            ['name' => 'edit-discrepancy'],

            //expenses--

            ['name' => 'view-expenses'],
            ['name' => 'create-expenses'],
            ['name' => 'edit-expenses'],
            ['name' => 'delete-expenses'],

            //expenses ledger

            ['name' => 'view-ledger expenses'],
            ['name' => 'create-ledger expenses'],
            ['name' => 'edit-ledger expenses'],
            ['name' => 'delete-ledger expenses'],

            //extra

            ['name' => 'view-extra'],
            ['name' => 'create-extra'],
            ['name' => 'edit-extra'],
            ['name' => 'delete-extra from guest'],//--
            ['name' => 'delete-extra'],

            //guest--

            ['name' => 'view-guest'],
            ['name' => 'create-guest'],
            ['name' => 'edit-guest'],
            ['name' => 'delete-guest'],
            ['name' => 'delete-attachment guest'],
            ['name' => 'checkout-guest'],
            ['name' => 'cancel-guest'],
            ['name' => 'checkin-guest'],
            ['name' => 'reinstate-guest'],
            ['name' => 'locked-reservisionguest'],

            // Profile permission--

            ['name' => 'view-profile'],
            ['name' => 'create-profile'],
            ['name' => 'edit-profile'],
            ['name' => 'delete-profile'],

            //invoice--

            ['name' => 'view-invoice'],
            ['name' => 'create-invoice'],
            ['name' => 'edit-invoice'],

            //window--

            ['name' => 'view-window'],
            ['name' => 'create-window'],

            //account customize--

            ['name' => 'view-account customize'],
            ['name' => 'create-account customize'],
            ['name' => 'edit-account customize'],
            ['name' => 'delete-account customize'],

            //activity log

            ['name' => 'view-activity log'],
            ['name' => 'create-activity log'],
            ['name' => 'edit-activity log'],
            ['name' => 'delete-activity log'],

            //blacklist--

            ['name' => 'view-blacklist'],
            ['name' => 'create-blacklist'],
            ['name' => 'edit-blacklist'],
            ['name' => 'delete-blacklist'],


            //cashier--

            ['name' => 'view-cashier'],
            ['name' => 'cashier-open'],
            ['name' => 'cashier-close'],

            //category

            ['name' => 'view-category'],
            ['name' => 'create-category'],
            ['name' => 'edit-category'],
            ['name' => 'delete-category'],

            //change room status

            ['name' => 'edit-change room status'],

            //close salse**************************************************************************--

            ['name' => 'view-closesalse'],
            ['name' => 'create-dataofcharge'],
            ['name' => 'create-dataofpayment'],

            // Assign permissions
            ['name' => 'view-assignpermissions'],
            ['name' => 'create-assignpermissions'],
            ['name' => 'edit-assignpermissions'],
            ['name' => 'create-permissions'],
            ['name' => 'edit-permissions'],
            ['name' => 'delete-permissions'],
            
            //company profile

            ['name' => 'view-companyprofile'],
            ['name' => 'create-companyprofile'],
            ['name' => 'edit-companyprofile'],
            ['name' => 'delete-companyprofile'],

            // Company statment--

            ['name' => 'view-companystatment'],
            ['name' => 'view-companyStatementbydates'],
            ['name' => 'create-companystatment'],
            ['name' => 'create-companyinvoices'],
            ['name' => 'create-advpayment'],
            ['name' => 'create-creditNote'],
            ['name' => 'create-debitNote'],
            ['name' => 'edit-companystatment'],
            ['name' => 'delete-companystatment'],

            //dashboard--

            ['name' => 'calculations-dashboards'],

            //day close--

            ['name' => 'view-dayclose'],
            ['name' => 'create-dayclose'],

            //day close out--

            ['name' => 'view-daycloseout'],
            ['name' => 'create-daycloseout'],

            // extend stay--

            ['name' => 'view-extendstay'],
            ['name' => 'create-extendstay'],
            ['name' => 'edit-extendstay'],
            ['name' => 'delete-extendstay'],

            // Floor permissions
            ['name' => 'create-floor'],
            ['name' => 'edit-floor'],
            ['name' => 'delete-floor'],
            ['name' => 'restore-deletedfloor'],
            ['name' => 'view-floor'],

            //folio statement--

            ['name' => 'create-foliostatement'],

            //get future data--

            ['name' => 'view-futuredata'],

            // integration--

            ['name' => 'view-integration'],
            ['name' => 'create-integration'],
            ['name' => 'edit-integration'],
            ['name' => 'delete-integration'],


            // label

            ['name' => 'view-label'],
            ['name' => 'create-label'],
            ['name' => 'edit-label'],
            ['name' => 'delete-label'],

            // Ledger permissions

            ['name' => 'view-ledger'],
            ['name' => 'create-ledger'],
            ['name' => 'edit-ledger'],
            ['name' => 'delete-ledger'],


            // localization--

            ['name' => 'view-localization'],
            ['name' => 'create-localization'],
            ['name' => 'edit-localization'],
            ['name' => 'delete-localization'],


            // lost and found

            ['name' => 'view-lostandfound'],
            ['name' => 'create-lostandfound'],
            ['name' => 'edit-lostandfound'],
            ['name' => 'delete-lostandfound'],


            // maintenance

            ['name' => 'view-maintenance'],
            ['name' => 'create-maintenance'],
            ['name' => 'edit-maintenance'],
            ['name' => 'delete-maintenance'],


            // maintenance type--

            ['name' => 'view-maintenancetype'],
            ['name' => 'create-maintenancetype'],
            ['name' => 'edit-maintenancetype'],
            ['name' => 'delete-maintenancetype'],


            // meal package--

            ['name' => 'view-mealpackage'],
            ['name' => 'create-mealpackage'],
            ['name' => 'edit-mealpackage'],
            ['name' => 'delete-mealpackage'],


            // meal--

            ['name' => 'view-meal'],
            ['name' => 'create-meal'],
            ['name' => 'edit-meal'],
            ['name' => 'delete-meal'],


            // notification--

            ['name' => 'view-notification'],
            ['name' => 'create-notification'],
            ['name' => 'edit-notification'],
            ['name' => 'delete-notification'],

            // out of ourder services--

            ['name' => 'view-oordservices'],
            ['name' => 'create-oordservices'],
            ['name' => 'edit-oordservices'],
            ['name' => 'delete-oordservices'],


            // payment--

            ['name' => 'view-payment'],
            ['name' => 'create-payment'],
            ['name' => 'edit-payment'],
            ['name' => 'delete-payment'],


            // public hotel page--

            ['name' => 'view-publichotelpage'],
            ['name' => 'create-publichotelpage'],
            ['name' => 'edit-publichotelpage'],
            ['name' => 'delete-publichotelpage'],


            // qr code--

            ['name' => 'create-qrcode'],

            // rate change history--

            ['name' => 'view-ratechangehistory'],
            ['name' => 'create-ratechangehistory'],
            ['name' => 'edit-ratechangehistory'],
            ['name' => 'prent-ratechangehistory'],
            ['name' => 'delete-ratechangehistory'],

            // rate code permi

            ['name' => 'view-ratecode'],
            ['name' => 'create-ratecode'],
            ['name' => 'edit-ratecode'],
            ['name' => 'attach-roomtypetoratecode'],
            ['name' => 'edit-roomtypetoratecode'],
            ['name' => 'delete-ratecode'],

            // report--

            ['name' => 'report-totPaymentOfCashierCloser'],
            ['name' => 'report-totpaymentofcashiersummary'],
            ['name' => 'report-productivitybycompany'],
            ['name' => 'report-productivitybycountry'],
            ['name' => 'report-productivitybysourcebusiness'],
            ['name' => 'report-productivitybyMarketsegments'],
            ['name' => 'report-guestledgerreport'],
            ['name' => 'report-voidedtransactions'],
            ['name' => 'report-transactionsdetails'],
            ['name' => 'report-transactionsdetailsbyuser'],
            ['name' => 'report-inhouseguestsreport'],
            ['name' => 'report-roomchangereport'],
            ['name' => 'report-ratechangereport'],
            ['name' => 'report-actualcheckedinandarrivalsreport'],
            ['name' => 'report-checkedoutanddeparturereport'],
            ['name' => 'report-revenuebydatesreport'],
            ['name' => 'report-revenuebycatbydate'],
            ['name' => 'report-revenuebydatesbyledgercat'],
            ['name' => 'report-receiptvoucherlistreport'],
            ['name' => 'report-paymentvoucherlistreport'],
            ['name' => 'report-roomsstatusreport'],
            ['name' => 'report-ooandosreport'],
            ['name' => 'report-reservationreport'],
            ['name' => 'report-resamount'],
            ['name' => 'report-incomestatementreport'],
            ['name' => 'report-incomestatementreportmtdytd'],
            ['name' => 'report-highbalancerepor'],
            ['name' => 'report-occupancybymonth'],
            ['name' => 'report-occupancybymtd'],
            ['name' => 'report-housekeepingbystatus'],
            ['name' => 'report-dailysalesreport'],
            ['name' => 'report-reservationbycompany'],
            ['name' => 'report-voucherlist'],
            ['name' => 'report-manager5report'],
            ['name' => 'report-mealplan'],
            ['name' => 'report-meals'],
            ['name' => 'report-salesinfuture'],
            ['name' => 'report-historyandforecast'],
            ['name' => 'report-monthwiserevenue'],
            ['name' => 'report-monthwiseoccupancy'],
            ['name' => 'report-daywiseoccupancy'],
            ['name' => 'report-liveoccupancycalculations'],
            ['name' => 'report-findfolio'],
            ['name' => 'report-transactionprinting'],
            ['name' => 'report-voucherprinting'],
            ['name' => 'report-roomguestshistory'],
            ['name' => 'report-recheckin'],
            ['name' => 'report-accountsstatement'],
            ['name' => 'report-accountstrailbalance'],
            ['name' => 'report-dhowinvoice'],
            ['name' => 'report-setupdaycloserecord'],
            ['name' => 'report-getreports'],
            ['name' => 'report-getreportsisdayclose'],
            ['name' => 'report-printmultireportsinonepdf'],

            // reservission status--

            ['name' => 'view-reservissionstatus'],
            ['name' => 'create-reservissionstatus'],
            ['name' => 'edit-reservissionstatus'],
            ['name' => 'delete-reservissionstatus'],


            // room status--

            ['name' => 'view-roomstatus'],
            ['name' => 'create-roomstatus'],
            ['name' => 'edit-roomstatus'],
            ['name' => 'delete-roomstatus'],

            // Roomtype permissions

            ['name' => 'view-roomtype'],
            ['name' => 'create-roomtype'],
            ['name' => 'edit-roomtype'],
            ['name' => 'delete-roomtype'],

            // search--

            ['name' => 'search-view'],
            ['name' => 'search-guests'],
            ['name' => 'search-reservation'],
            ['name' => 'search-returnarrivals'],
            ['name' => 'search-departuresguests'],
            ['name' => 'search-returncancellationguests'],

            // settings--no front

            ['name' => 'view-settings'],
            ['name' => 'edit-settings'],


            // Tax permissions

            ['name' => 'view-tax'],
            ['name' => 'create-tax'],
            ['name' => 'edit-tax'],
            ['name' => 'delete-tax'],


            // tenant--

            ['name' => 'view-tenant'],
            ['name' => 'create-tenant'],
            ['name' => 'edit-tenant'],
            ['name' => 'delete-tenant'],


            // ticket

            ['name' => 'view-ticket'],
            ['name' => 'create-ticket'],
            ['name' => 'edit-ticket'],
            ['name' => 'delete-ticket'],
            ['name' => 'upload-ticket'],
            ['name' => 'close-ticket'],
            ['name' => 'reopen-ticket'],
            ['name' => 'archive-ticket'],

            // transfer transaction--

            ['name' => 'view-transfertransaction'],
            ['name' => 'create-transfertransaction'],

            // Room permissions

            ['name' => 'view-room'],
            ['name' => 'view-deletedroom'],
            ['name' => 'create-room'],
            ['name' => 'edit-room'],
            ['name' => 'delete-room'],
            ['name' => 'restore-deletedroom'],


            //room change--

            ['name' => 'view-roomchange'],
            ['name' => 'create-roomchange'],

            //view garden--

            ['name' => 'view-viewgarden'],
            ['name' => 'create-viewgarden'],
            ['name' => 'edit-viewgarden'],
            ['name' => 'delete-viewgarden'],



            // Roles permissions--

            ['name' => 'view-roles'],
            ['name' => 'create-roles'],
            ['name' => 'edit-roles'],
            ['name' => 'delete-roles'],
            ['name' => 'assign-permissionforrole'],//needs a look--

            // Plan permissions

            ['name' => 'view-plan'],
            ['name' => 'create-plan'],
            ['name' => 'edit-plan'],
            ['name' => 'delete-plan'],
            ['name' => 'viewdeleted-plan'],
            ['name' => 'restoredeleted-plan'],
            ['name' => 'forcedeleted-plan'],

            // Feature permissions

            ['name' => 'view-feature'],
            ['name' => 'create-feature'],
            ['name' => 'edit-feature'],
            ['name' => 'delete-feature'],
            ['name' => 'viewdelete-feature'],
            ['name' => 'restoredelete-feature'],
            ['name' => 'forcedelete-feature'],


            // Market segment permissions

            ['name' => 'view-market segment'],
            ['name' => 'create-market segment'],
            ['name' => 'edit-market segment'],
            ['name' => 'delete-market segment'],

            // Source of business permissions

            ['name' => 'view-source of business'],
            ['name' => 'create-source of business'],
            ['name' => 'edit-source of business'],
            ['name' => 'delete-source of business'],

            // for all permissions
            ['name' => 'manage-all'],

            //////////////////////////////////////////////////////////////////////////////////////////
        ];


        foreach ($permissions as $set) {
            Permission::create($set);
        }
    }
}
