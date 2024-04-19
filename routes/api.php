<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FloorController;

use App\Http\Controllers\LabelController;
use App\Http\Controllers\MealsController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RateCodeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BlackListController;
use App\Http\Controllers\CloseSalesController;
use App\Http\Controllers\DashBoardsController;
use App\Http\Controllers\ExtendStayController;
use App\Http\Controllers\RoomStatusController;
use App\Http\Controllers\ActivitylogController;
use App\Http\Controllers\CalculationController;
use App\Http\Controllers\ClearTablesController;
use App\Http\Controllers\DayCloseOutController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\MealPackageController;
use App\Http\Controllers\OordServiceController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\LostAndFoundController;
use App\Http\Controllers\Api\V1\ApiKeyController;
use App\Http\Controllers\companyProfileController;
use App\Http\Controllers\FolioStatementController;
use App\Http\Controllers\SourceBusinessController;
use App\Http\Controllers\MaintenanceTypeController;
use App\Http\Controllers\PublicHotelPageController;
use App\Http\Controllers\AccountCustomizeController;
use App\Http\Controllers\ChangeRoomStatusController;
use App\Http\Controllers\CompanyStatementController;
use App\Http\Controllers\IntegrationTableController;
use App\Http\Controllers\Api\V1\Rooms\RoomController;
use App\Http\Controllers\RateChangeHistoryController;
use App\Http\Controllers\ReservationStatusController;
use App\Http\Controllers\RoomStatusHistoryController;
use App\Http\Controllers\Api\V1\Rooms\TowerController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\TransferTransactionController;
use App\Http\Controllers\GetFutureDateFunctionController;
use App\Http\Controllers\Api\V1\Permission\RoleController;
use App\Http\Controllers\Api\V1\History\DayCloseController;
use App\Http\Controllers\Api\V1\Rooms\RoomChangeController;
use App\Http\Controllers\Api\V1\Rooms\ViewGardenController;
use App\Http\Controllers\Api\V1\Subscription\PlanController;
use App\Http\Controllers\Api\V1\Rooms\DayCloseProcesController;
use App\Http\Controllers\Api\V1\Subscription\FeatureController;
use App\Http\Controllers\Api\V1\Permission\PermissionController;
use App\Http\Controllers\Api\V1\Profiles\Calculates\BudgetController;
use App\Http\Controllers\Api\V1\Profiles\GuestProfile\WindowController;
use App\Http\Controllers\Api\V1\Profiles\Calculates\PreChargeController;
use App\Http\Controllers\Api\V1\Profiles\GuestProfile\InvoiceController;
use App\Http\Controllers\Api\V1\Profiles\Calculates\DirectBillController;
use App\Http\Controllers\Api\V1\Profiles\GuestProfile\ExpensesController;

//use App\Http\Controllers\Api\V1\Profiles\GuestProfile\ReservationController;

use App\Http\Controllers\Api\V1\Profiles\GuestProfile\DiscrepancyController;
use App\Http\Controllers\Api\V1\Profiles\GuestProfile\ReservationController;
use App\Http\Controllers\Api\V1\Profiles\Calculates\DayCloseRecordController;
use App\Http\Controllers\Api\V1\Profiles\GuestProfile\ChargeRoutedController;
use App\Http\Controllers\Api\V1\Profiles\GuestProfile\GuestInhouseController;
use App\Http\Controllers\Api\V1\Profiles\GuestProfile\GuestProfileController;
use App\Http\Controllers\Api\V1\Profiles\GuestProfile\ExpensesLedgerController;
use App\Http\Controllers\Api\V1\Profiles\GuestProfile\ChartOfAccountsController;
use App\Http\Controllers\Api\V1\Profiles\GuestProfile\ExtraBedAndMealController;
use App\Http\Controllers\Api\V1\Profiles\Calculates\JournalVoucherTypeController;
use App\Http\Controllers\Api\V1\Profiles\Calculates\JournalVoucherMasterAndDetailsController;

Route::post('user-register', [AuthController::class, 'register']);
Route::resource('user', UserController::class);
Route::post('user-login', [AuthController::class, 'login']);
Route::post('testapi', [TestController::class, 'testntmp']);
Route::post('calc', function () {
    return CalculationController::taxes(5, 1, 150.89);
});
Route::get('check-tenant', [TenantController::class,'checkTenant']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('user-logout', [AuthController::class, 'logout']);
    Route::get('userPagination/{id}', [UserController::class, 'userPagination']);
    Route::post('change-password-by-user', [UserController::class, 'changePasswordByUser']);
    Route::post('pay', [CompanyStatementController::class, 'payment']);
    Route::post('adv-pay', [CompanyStatementController::class, 'AdvPayment']);
    Route::post('credit', [CompanyStatementController::class, 'creditNote']);
    Route::post('debit', [CompanyStatementController::class, 'debitNote']);
    Route::post('company-invoices', [CompanyStatementController::class, 'CompanyInvoices']);
    Route::post('cashier-open', [CashierController::class, 'Cashier_Open']);
    Route::post('cashier-close', [CashierController::class, 'Cashier_Close']);
    Route::get('getOpenCashier', [CashierController::class, 'getOpenCashier']);
    Route::post('closeCashiers', [CashierController::class, 'closeCashiers']);
    Route::post('checkOut', [ReservationController::class, 'checkOut']);

    Route::post('cityLedgerCheckout', [ReservationController::class, 'cityLedgerCheckout']);
    Route::get('notify', [UserController::class, 'Notifications']);
    Route::get('mark-as-read/{id}', [UserController::class, 'MarkAsRead']);
    Route::delete('delete-notification/{id}', [UserController::class, 'deleteNotification']);


    Route::apiResource('black_list', BlackListController::class);

    Route::get('qrCode', [QrCodeController::class, 'GenerateQr_Code']);
    Route::get('download', [QrCodeController::class, 'download']);

    Route::apiResource('extend-stay', ExtendStayController::class);
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::get('categoryPagination/{id}', [CategoryController::class, 'categoryPagination']);
    Route::resource('labels', LabelController::class);
    Route::get('labelPagination/{id}', [LabelController::class, 'labelPagination']);
    Route::resource('tickets', TicketController::class);
    Route::get('ticketsPagination/{id}', [TicketController::class, 'ticketPagination']);
    Route::post('tickets/upload', [TicketController::class, 'upload']);
    Route::post('tickets/{ticket}/close', [TicketController::class, 'close']);
    Route::post('tickets/{ticket}/reopen', [TicketController::class, 'reopen']);
    Route::post('tickets/{ticket}/archive', [TicketController::class, 'archive']);
    Route::resource('settings', SettingsController::class);

    Route::resource('room_types', RoomTypeController::class);
    Route::get('room_types_pagination/{id}', [RoomTypeController::class, 'roomTypePagiantion']);

    Route::resource('tax', TaxController::class);
    Route::get('taxPagination/{id}', [TaxController::class, 'taxPagination']);
    Route::resource('ledger', LedgerController::class);
    Route::get('ledger-cats', [LedgerController::class, 'ledger_cats']);
    Route::resource('activities', ActivitylogController::class);
    Route::post('getActivityByFilter', [ActivitylogController::class, 'getActivityByFilter']);
    Route::get('activityPagination/{id}', [ActivitylogController::class, 'activityPagination']);

    Route::resource('meals', MealsController::class);
    Route::get('mealsPagination/{id}', [MealsController::class, 'mealsPagination']);
    Route::resource('meal-packages', MealPackageController::class);
    Route::get('meal-packages_pagination/{id}', [MealPackageController::class, 'mealPackagePagination']);
    Route::resource('reservation-status', ReservationStatusController::class);

    Route::post('test-trans', [TransactionController::class, 'test']);
    Route::resource('transactions', TransactionController::class);
    Route::get('transactionsPagination/{id}', [TransactionController::class, 'transactionPagination']);

    Route::resource('lost-and-found', LostAndFoundController::class);
    Route::get('lost-and-found_pagination/{id}', [LostAndFoundController::class, 'lostAndFoundPagination']);

    Route::resource('reservation-status', ReservationStatusController::class);
    Route::get('reservation-status_pagiantion/{id}', [ReservationStatusController::class, 'rservationStatusPagination']);

    Route::post('storeMoreOneRoom', [RoomController::class, 'storeMoreOneRoom']);
    Route::apiResource('rooms', RoomController::class);
    Route::post('copyRoomsFromFloorToAnother', [RoomController::class, 'copyRoomsFromFloorToAnother']);

    Route::apiResource('tower', TowerController::class);


    Route::get('getAllInhousRooms', [RoomController::class, 'getAllInhousRooms']);
    Route::post('filter-rooms', [RoomController::class, 'roomsFilter']);
    Route::get('roomPagination/{id}', [RoomController::class, 'roomPagination']);

    Route::get('getAllInhousRooms', [RoomController::class, 'getAllInhousRooms']);
    Route::post('filter-rooms', [RoomController::class, 'roomsFilter']);
    Route::get('roomPagination/{id}', [RoomController::class, 'roomPagination']);
    Route::post('roomStatus', [RoomController::class, 'roomStatus']);
    Route::get('get-max-sort-order', [RoomController::class, 'Max_sortOrder']);
    Route::get('rearrange-sort-order', [RoomController::class, 'Rearrange_sort_order']);


    Route::get('getRoomsDeleted', [RoomController::class, 'geSoftDeletedData']);
    Route::post('restorRoomDataTrashed/{id}', [RoomController::class, 'restorDataTrashed']);

    Route::resource('rate-code', RateCodeController::class);
    Route::get('rate-code_pagination/{id}', [RateCodeController::class, 'ratePagination']);
    Route::post('attach-roomtype-ratecode/{id}', [RateCodeController::class, 'attach_room_type_to_rate_code']);
    Route::post('deatach-roomtype-ratecode/{id}', [RateCodeController::class, 'update_room_type_to_rate_code']);

    Route::resource('maintenance-type', MaintenanceTypeController::class);
    Route::get('maintenanceTypePagination/{id}', [MaintenanceTypeController::class, 'maintenanceTypePagination']);

    Route::resource('maintenance', MaintenanceController::class);
    Route::get('mainTenancePagination/{id}', [MaintenanceController::class, 'mainTenancePagination']);

    Route::get('room-maintenance/{id}', [MaintenanceController::class, 'get_room_maintenances']);
    Route::post('maintenances-date', [MaintenanceController::class, 'maintenances_date_with_filter']);

    Route::resource('room-status', RoomStatusController::class);
    Route::get('room-status_pagination/{id}', [RoomStatusController::class, 'roomStatus']);


    Route::apiResource('guestInhouse', GuestInhouseController::class);
    Route::get('inhousPagination/{id}', [GuestInhouseController::class, 'inhousPagination']);

    Route::resource('change-room-status', ChangeRoomStatusController::class);

    Route::apiResource('guestInhouse', GuestInhouseController::class);
    Route::get('inhousPagination/{id}', [GuestInhouseController::class, 'inhousPagination']);



    Route::post('transaction_collection', [TransactionController::class, 'transaction_collection']);
    Route::get('calculate_excluded', [TransactionController::class, 'calculate_excluded']);

    Route::apiResource('ratechange', RateChangeHistoryController::class);
    Route::post('rate-change-print', [RateChangeHistoryController::class, 'Change_Rate_Print']);
    Route::post('room-change-print', [RateChangeHistoryController::class, 'Change_Room_Print']);
    Route::apiResource('reservation', ReservationController::class);
    Route::post('getGuestInhous', [ReservationController::class, 'getGuestInhous']);
    Route::post('getGuestInhouswithDates', [ReservationController::class, 'getGuestInhouswithDates']);
    Route::post('storeOnlyName', [ReservationController::class, 'storeOnlyName']);
    Route::post('storeGroupReservision', [ReservationController::class, 'groupReservision']);
    Route::post('updateGroupGuest', [ReservationController::class, 'updateGroupGuest']);
    Route::post('updateGroupGuestByIds', [ReservationController::class, 'updateGroupGuestByIds']);
    Route::get('showGroupRservision/{id}', [ReservationController::class, 'showGroupRservision']);
    Route::get('showGroupRservisionForSelected/{id}', [ReservationController::class, 'showGroupRservisionForSelected']);
    Route::post('storeGroupProfile', [ReservationController::class, 'storeGroupProfile']);
    Route::post('storeMoreName', [ReservationController::class, 'storeMoreName']);
    Route::put('updateMoreName/{id}', [ReservationController::class, 'updateMoreName']);
    Route::get('reservationPagination/{id}', [ReservationController::class, 'reservationPagination']);
    Route::put('checkIn/{id}', [ReservationController::class, 'checkIn']);
    Route::post('groupCheckIn', [ReservationController::class, 'groupCheckIn']);
    Route::post('cancel', [ReservationController::class, 'cancel']);
    Route::post('reInstate/{id}', [ReservationController::class, 'reInstate']);
    Route::post('availability', [ReservationController::class, 'availability']);
    Route::post('availabilityScreenData', [ReservationController::class, 'availabilityScreenData']);

    Route::apiResource('window', WindowController::class);

    Route::apiResource('roomChange', RoomChangeController::class);
    Route::get('roomChangPagination/{id}', [RoomChangeController::class, 'roomChangPagination']);


    Route::get('profilePagination/{id}', [GuestProfileController::class, 'profilePagination']);
    Route::post('store_to_landlord', [GuestProfileController::class, 'store_to_landlord']);
    Route::get('store_from_landlord/{id}', [GuestProfileController::class, 'store_from_landlord']);
    Route::post('guestProfile', [GuestProfileController::class, 'store']);
    Route::apiResource('guestProfile', GuestProfileController::class);
    Route::post('guest_profile_search', [GuestProfileController::class, 'guest_profile_search']);
    Route::get('idtype', [GuestProfileController::class, 'getIdType']);
    Route::get('storePreChargeData/{id}', [PreChargeController::class, 'storePreChargeData']);

    Route::apiResource('extend-stay', ExtendStayController::class);

    Route::post('print-extend-stay', [ExtendStayController::class, 'Extend_Stay_Print']);

    Route::apiResource('invoice', InvoiceController::class);
    Route::get('invoicePagination/{id}', [InvoiceController::class, 'invoicePagination']);
    Route::get('print-invoice-qr-image', [InvoiceController::class, 'Generate_imageqrCode']);

    ///////////////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\//////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    // Route::post('checkOut', [ReservationController::class, 'checkOut']);
    //  Route::post('recheckIn', [ReservationController::class,'ReCheckin']);
    Route::post('convertOnlyNameToNormalReservision', [ReservationController::class, 'convertOnlyNameToNormalReservision']);
    Route::post('lockedReservision/{id}', [ReservationController::class, 'lockedReservision']);

    Route::get('getExpectedCheckOut', [DayCloseProcesController::class, 'getExpectedCheckOut']);
    Route::post('extendStay', [DayCloseProcesController::class, 'extendStay']);
    Route::post('extendStayForManyGuest', [DayCloseProcesController::class, 'extendStayForManyGuest']);
    Route::get('getExpectedCheckIn', [DayCloseProcesController::class, 'getExpectedCheckIn']);
    Route::post('noShow', [DayCloseProcesController::class, 'noShow']);
    Route::get('OrderAndService', [DayCloseProcesController::class, 'OrderAndService']);
    Route::post('ToExtendOOrdServicesOneDay', [DayCloseProcesController::class, 'ToExtendOOrdServicesOneDay']);
    Route::get('getPrechargeDataTransfearToTransaction', [DayCloseProcesController::class, 'getPrechargeDataTransfearToTransaction']);
    Route::post('storeFromPreChargeToTransaction', [DayCloseProcesController::class, 'storeFromPreChargeToTransaction']);


    Route::post('finalDayClose', [DayCloseProcesController::class, 'finalDayClose']);

    Route::apiResource('ChartOfAccounts', ChartOfAccountsController::class);
    Route::post('indexWithBalance', [ChartOfAccountsController::class, 'indexWithBalance']);


    Route::apiResource('journalVoucher', JournalVoucherMasterAndDetailsController::class);
    Route::post('print-journal-voucher', [JournalVoucherMasterAndDetailsController::class, 'Accounts_Print_journal_voucher']);

    Route::get('discrepancyShowById/{id}', [DiscrepancyController::class, 'showById']);
    Route::apiResource('discrepancy', DiscrepancyController::class);
    Route::apiResource('directBill', DirectBillController::class);

    Route::apiResource('viewGarden', ViewGardenController::class);

    Route::apiResource('ExtraBedAndMeal', ExtraBedAndMealController::class);


    Route::post('storeExtraqForGuest', [ExtraBedAndMealController::class, 'storeExtraqForGuest']);
    Route::post('storeExtraqForGuestToTransaction', [ExtraBedAndMealController::class, 'storeExtraqForGuestToTransaction']);

    Route::post('guestAttachment', [ReservationController::class, 'guestAttachment']);

    Route::delete('guestDeleteAttachment/{id}', [ReservationController::class, 'guestDeleteAttachment']);
    Route::post('updateRateDays', [ReservationController::class, 'updateRateDays']);
    Route::post('GetOcGuestByData', [ReservationController::class, 'GetOcGuestByData']);
    Route::post('reservision-chart-summary', [ReservationController::class, 'reservisionChartSummary']);
    Route::post('GetOcGuestByData', [ReservationController::class, 'GetOcGuestByDate']);
    Route::post('reservation-chart', [ReservationController::class, 'reservationChart']);
    Route::get('getExtraByGuestID/{id}', [ExtraBedAndMealController::class, 'getExtraByGuestID']);
    Route::get('getExteraOfCheckInGuest', [ExtraBedAndMealController::class, 'getExteraOfCheckInGuest']);
    Route::delete('destroyExtraGuestPivot/{id}', [ExtraBedAndMealController::class, 'destroyExtraGuestPivot']);
    Route::post('companystatemen_dates', [CompanyStatementController::class, 'CompanyStatement_ByDates']);

    Route::get('getAllPermissios', [PermissionController::class, 'index']);

    Route::get('getAllPermissionsPagination/{id}', [PermissionController::class, 'getAllPermissionsPagination']);

    Route::get('getAllRoles', [RoleController::class, 'index']);
    Route::get('getAllRolePaginate/{id}', [RoleController::class, 'getAllRolePaginate']);
    Route::get('getAllPermissios/{id}', [PermissionController::class, 'index']);
    Route::post('getPermissionById/{id}', [PermissionController::class, 'getPermissionByID']);
    Route::post('updatePermission/{id}', [PermissionController::class, 'update']);
    Route::post('storePermission', [PermissionController::class, 'store']);
    Route::post('permissionDelete/{id}', [PermissionController::class, 'delete']);
    Route::post('updateUserPermissions/{id}', [PermissionController::class, 'updateUserPermissions']);

    Route::get('getAllRoles', [RoleController::class, 'index']);
    Route::get('getAllRoles/{id}', [RoleController::class, 'getAllRolePagination']);
    Route::post('getRoleById/{id}', [RoleController::class, 'getRoleByID']);
    Route::post('storeRole', [RoleController::class, 'store']);
    Route::post('updateRole/{id}', [RoleController::class, 'updateRole']);
    Route::post('assignPermissionRole/{id}', [RoleController::class, 'assignPermissionForRole']);
    Route::post('deleteRole/{id}', [RoleController::class, 'destroy']);

    Route::get('getAllPermissionPaginate{id}', [PermissionController::class, 'getAllPermissionPaginate']);
    Route::post('getPermissionById/{id}', [PermissionController::class, 'getPermissionForUser']);
    Route::post('updatePermission', [PermissionController::class, 'update']);
    Route::post('permissionDelete/{id}', [PermissionController::class, 'delete']);
    Route::get('getPermissionBySubject', [PermissionController::class, 'getPermissionBySubject']);
    Route::post('assignPermissionUser', [PermissionController::class, 'assignPermissionUser']);

    Route::get('getUserPermissionByID/{id}', [PermissionController::class, 'getUserPermissionByID']);
    Route::post('guest_filter', [GuestInhouseController::class, 'GuestsFilter']);

    Route::get('segment/{id}', [MarketController::class, 'marketPagination']);
    Route::resource('source_business', SourceBusinessController::class);
    Route::get('source_businessPagination/{id}', [SourceBusinessController::class, 'sourceBusinessPagination']);
    Route::apiResource('companyProfile', companyProfileController::class);
    Route::get('companyProfilePagination/{id}', [companyProfileController::class, 'companyPagination']);
    Route::resource('payment', PaymentController::class);
    Route::get('paymentPagination/{id}', [PaymentController::class, 'paymentPagination']);
    Route::get('paymentmode', [PaymentController::class, 'getPaymentMode']);

    Route::post('search_guests', [SearchController::class, 'search_guests']);
    Route::resource('sourcebusiness', SourceBusinessController::class);
    Route::resource('segment', MarketController::class);
    Route::get('segmentPaginate/{id}', [MarketController::class, 'marketPagination']);
    Route::get('sourcebusinessPagination/{id}', [SourceBusinessController::class, 'sourceBusinessPagination']);
    Route::get('getcountries', [companyProfileController::class, 'getcountries']);


    Route::apiResource('budget', BudgetController::class);
    Route::put('updateBudget', [BudgetController::class, 'update']);
    Route::apiResource('expensesLedger', ExpensesLedgerController::class);
    Route::apiResource('expenses', ExpensesController::class);


    Route::get('taxes-calculation', [CalculationController::class, 'taxes']);

    //Route::get('users',[AuthController::class,'users']);

    Route::resource('language', LocalizationController::class);
    Route::get('localizationPagination/{id}', [LocalizationController::class, 'localizationPagination']);



    /* Route::get('getAllRoles', [RoleController::class, 'index']);
Route::post('ruleById', [RoleController::class, 'getRoleForUser']);
Route::post('getRoleById', [RoleController::class, 'getPermissionForUser']);
Route::post('storeRole', [RoleController::class, 'store']);
Route::post('updateRole', [RoleController::class, 'updateRole']);
Route::post('deleteRole/{id}', [RoleController::class, 'destroy']); */

    Route::apiResource('plan', PlanController::class);
    Route::get('planPagination/{id}', [PlanController::class, 'planPagination']);
    Route::post('assosiative/{id}', [PlanController::class, 'createPlanFeature']);
    Route::get('getSoftDeletedData', [PlanController::class, 'geSoftDeletedData']);
    Route::post('restorDataTrashed/{id}', [PlanController::class, 'restorDataTrashed']);
    Route::post('planForceDelete/{id}', [PlanController::class, 'DBDelete']);
    Route::post('createPlanForTenant', [PlanController::class, 'createPlanForTenant']);

    Route::apiResource('feature', FeatureController::class);
    Route::get('featurePagination/{id}', [FeatureController::class, 'featurePagination']);
    Route::get('getSoftDeletedFeatureData', [FeatureController::class, 'geSoftDeletedData']);
    Route::post('restorFeatureDataTrashed/{id}', [FeatureController::class, 'restorDataTrashed']);
    Route::post('featureForceDelete/{id}', [FeatureController::class, 'DBDelete']);
    Route::resource('floor', FloorController::class);
    Route::get('max-sort-order', [FloorController::class, 'Max_sort_order']);
    Route::get('floorPaginate/{id}', [FloorController::class, 'floorPaginat']);
    Route::post('restoreFloorDataTrashed/{id}', [FloorController::class, 'restoreTrashed']);
    Route::get('get-floors-with-rooms', [FloorController::class, 'get_floors_with_rooms']);
    Route::post('get-floor-with-rooms', [FloorController::class, 'get_floor_with_rooms']);
    Route::get('get-floors-without-rooms', [FloorController::class, 'get_floors_without_rooms']);
    /////////////////////////////////////////////////////////////////////////////



    ///////////////////////////////////////////////////////////////////////////////


    Route::resource('tenant', TenantController::class);

    Route::get('tenantPagination/{id}', [TenantController::class, 'tenantPagination']);
    //Route::resource('guestProfile',GuestProfileController::class);

    Route::apiResource('companystate', CompanyStatementController::class);
    Route::get('companySatatePagination/{id}', [CompanyStatementController::class, 'companySatatePagination']);
    Route::post('companystatemen_dates', [CompanyStatementController::class, 'CompanyStatement_ByDates']);
    // Route::post('pay',[CompanyStatementController::class,'payment']);
    // Route::post('adv-pay',[CompanyStatementController::class,'AdvPayment']);  
    // Route::post('credit',[CompanyStatementController::class,'creditNote']);    
    // Route::post('debit',[CompanyStatementController::class,'debitNote']); 
    Route::post('void/{id}', [CompanyStatementController::class, 'void']);
    Route::get('state_acc', [CompanyStatementController::class, 'statement_acc']);
    Route::get('calc_balance/{id}', [CompanyStatementController::class, 'returns']);
    Route::post('recheck', [GuestInhouseController::class, 'recheck']);



    Route::post('reservation_search', [SearchController::class, 'Reservation_Search']);
    Route::post('return_arrivals', [SearchController::class, 'Return_Arrivals']);
    Route::post('departures_guests', [SearchController::class, 'Departures_Guests']);
    Route::post('return_cancellation_guests', [SearchController::class, 'Return_Cancellation_Guests']);

    Route::delete('delete/{id}', [BlackListController::class, 'SoftDelete']);
    Route::post('guest_profile_search', [GuestProfileController::class, 'guest_profile_search']);
    Route::post('seachProfile', [GuestProfileController::class, 'seachProfile']);

    Route::get('close-sales', [CloseSalesController::class, 'get_Total_Of_Charge_ByLedger']);
    Route::get('close-sales_pay', [CloseSalesController::class, 'get_Count_Of_payment']);
    Route::get('StoreData_Of_Charge', [CloseSalesController::class, 'StoreData_Of_Charge']);
    Route::get('StoreData_Of_Payment', [CloseSalesController::class, 'StoreData_Of_Payment']);
    Route::post('display_data_fromController', [CloseSalesController::class, 'index']);

    Route::post('Tot_Payment_Of_Cashier_Closer', [ReportsController::class, 'Tot_Payment_Of_Cashier_Closer']);
    Route::post('Tot_Payment_Of_Cashier_Summary', [ReportsController::class, 'Tot_Payment_Of_Cashier_Summary']);
    Route::post('ProductivityByCompany', [ReportsController::class, 'ProductivityByCompany']);
    Route::post('ProductivityByCountry', [ReportsController::class, 'ProductivityByCountry']);
    Route::post('ProductivityBySource_Business', [ReportsController::class, 'ProductivityBySource_Business']);
    Route::post('ProductivityByMarket_Segments', [ReportsController::class, 'ProductivityByMarket_Segments']);
    Route::post('guest_ledger_report', [ReportsController::class, 'Guest_Ledger_Report']);
    Route::post('voided_transactions', [ReportsController::class, 'Voided_Transactions']);
    Route::post('Floor_by_rooms', [FloorController::class, 'Floor_by_rooms']);
    Route::post('transactions-details', [ReportsController::class, 'Transactions_Details']);
    Route::post('transactions-details-by-user', [ReportsController::class, 'Transactions_Details_By_User']);
    Route::post('Inhouse-guests-report', [ReportsController::class, 'InHouse_Guests_Report']);
    Route::post('rate_change_report', [ReportsController::class, 'Rate_Change_Report']);
    Route::post('room_change_report', [ReportsController::class, 'Room_Change_Report']);
    Route::post('actual-checkedIn-and-arrivals-report', [ReportsController::class, 'Actual_CheckedIn_And_Arrivals_Report']);
    Route::post('checkedOut-and-departure-report', [ReportsController::class, 'Checked_Out_And_Departure_Report']);
    Route::post('revenue-by-dates-report', [ReportsController::class, 'Revenue_By_Dates_Report']);
    Route::post('receipt-voucher-list-report', [ReportsController::class, 'Receipt_Voucher_List_Report']);
    Route::post('payment-voucher-list-report', [ReportsController::class, 'Payment_Voucher_List_Report']);
    Route::post('rooms-status-report', [ReportsController::class, 'Rooms_Status_Report']);
    Route::post('OO-and-oS-report', [ReportsController::class, 'OO_And_OS_Report']);
    Route::post('reservation-report', [ReportsController::class, 'Reservation_Report']);
    Route::post('res-amount', [ReportsController::class, 'Res_Amount']);
    Route::post('income-statement-report', [ReportsController::class, 'Income_Statement_Report']);
    Route::post('income_statement_MTD_YTD', [ReportsController::class, 'Income_Statement_Report_MTD_YTD']);
    Route::get('high-balance-report', [ReportsController::class, 'High_Balance_Report']);
    Route::post('occupancy-by-month', [ReportsController::class, 'Occupancy_By_Month']);
    Route::post('occupancy-by-mtd', [ReportsController::class, 'Occupancy_By_MTD']);
    Route::get('house-keeping-by-status', [ReportsController::class, 'House_Keeping_By_Status']);
    Route::post('daily-sales-report', [ReportsController::class, 'Daily_Sales_Report']);
    Route::post('reservations-by-company', [ReportsController::class, 'Reservation_By_Company']);
    Route::post('voucher-list', [ReportsController::class, 'Voucher_List']);
    Route::post('transaction-list-view', [ReportsController::class, 'Transactions_List_View']);
    Route::post('manager1-report', [ReportsController::class, 'Manager1_Report']);
    Route::post('manager5-report', [ReportsController::class, 'Manager5_Report']);
    Route::post('print-invoice', [ReportsController::class, 'Print_Invoice']);
    Route::post('meal-plan', [ReportsController::class, 'Meal_Plan']);
    Route::post('history-and-forecast', [ReportsController::class, 'History_And_Forecast']);
    Route::post('monthWise_revenue', [ReportsController::class, 'Monthwise_Revenue']);
    Route::post('monthWise_occupancy', [ReportsController::class, 'Monthwise_Occupancy']);

    Route::post('dayWise_occupancy', [ReportsController::class, 'Daywise_Occupancy']);
    Route::post('live_occupancy', [ReportsController::class, 'Live_Occupancy_Calculations']);

    Route::post('find_folio', [ReportsController::class, 'Find_Folio']);
    Route::post('transactionPrinting', [ReportsController::class, 'Transaction_Printing']);
    Route::post('voucherPrinting', [ReportsController::class, 'Voucher_Printing']);
    Route::post('roomGuest-history', [ReportsController::class, 'Room_Guests_History']);

    Route::post('recheck', [ReportsController::class, 'recheckIn']);

    Route::post('find-folio', [ReportsController::class, 'Find_Folio']);
    Route::post('account-state', [ReportsController::class, 'Accounts_Statement']);
    Route::post('account-balance', [ReportsController::class, 'indexWithBalance']);
    Route::get('show-invoice/{uuid}', [ReportsController::class, 'ShowInvoice']);


    Route::get('dashboard-calculations', [DashBoardsController::class, 'Dashboards_Calculations']);
    Route::post('set-up-day-close', [ReportsController::class, 'SetUp_DayCloseRecord']);
    Route::get('get_reports', [ReportsController::class, 'Get_Reports']);
    Route::get('get_reports_is_dayClose', [ReportsController::class, 'Get_Reports_Is_Day_Close']);
    Route::get('print-multi-reports', [ReportsController::class, 'Print_MultiReports_in_one_pdf']);
    Route::post('get-updated-reservation', [ReportsController::class, 'GetUpdatedReservation']);
    Route::post('transaction-taxes', [ReportsController::class, 'Transaction_Taxes']);



    //    Route::get('dashboard-calculations', [DashBoardsController::class, 'Dashboards_Calculations']);
    Route::post('delete-table-trans', [ClearTablesController::class, 'deleteTransaction']);
    Route::post('delete-table-master', [ClearTablesController::class, 'deleteMaster']);


    Route::apiResource('room-status-history', RoomStatusHistoryController::class);
    Route::post('insert-status-change', [RoomStatusHistoryController::class, 'InsertStatusChange']);
    Route::post('get-room-id', [RoomStatusHistoryController::class, 'GetByRoomID']);



    Route::apiResource('room-status-history', RoomStatusHistoryController::class);
    Route::post('insert-status-change', [RoomStatusHistoryController::class, 'InsertStatusChange']);
    Route::post('get-room-id', [RoomStatusHistoryController::class, 'GetByRoomID']);




    Route::apiResource('account-customize', AccountCustomizeController::class);
    Route::post('account-profit-loss', [AccountCustomizeController::class, 'Accounts_Profit_and_Loss']);
    Route::post('balance-sheet', [AccountCustomizeController::class, 'Balance_Sheet']);
    Route::post('get-cr-dr', [AccountCustomizeController::class, 'Get_CR_DR']);


    Route::apiResource('public-home-page', PublicHotelPageController::class);


    Route::apiResource('integration_table', IntegrationTableController::class);
    Route::get('get_value_byKey/{id}/{key}', [IntegrationTableController::class, 'Get_Value_BY_Key']);
    Route::post('number-of-arrival', [GetFutureDateFunctionController::class, 'Number_of_Arrival']);
    Route::post('number-of-departures', [GetFutureDateFunctionController::class, 'Number_of_Departures']);
    Route::post('get-room-Revenue', [GetFutureDateFunctionController::class, 'Get_Room_Revenue']);
    Route::post('get-FBRevenue', [GetFutureDateFunctionController::class, 'Get_FBRevenue']);
    Route::post('get-Oc-Rooms', [GetFutureDateFunctionController::class, 'Get_Oc_Rooms']);
    Route::post('get-Oc-Rooms-percent', [GetFutureDateFunctionController::class, 'Get_Oc_Rooms_Percent']);


    Route::apiResource('transfer-transaction', TransferTransactionController::class);
    Route::post('transferTransaction-search-by-date', [TransferTransactionController::class, 'TransferTransaction_Search_By_Date']);

    Route::apiResource('Oord_services', OordServiceController::class);
    Route::put('return-oord/{id}', [OordServiceController::class, 'returnOORD']);
    Route::get('oOrdServicesPagination/{id}', [OordServiceController::class, 'oOrdServicesPagination']);
    Route::post('change/{id}', [OordServiceController::class, 'changeRoom']);
    Route::post('return/{id}', [OordServiceController::class, 'returnRoom']);
    Route::get('ratechangePagination/{id}', [RateChangeHistoryController::class, 'rateChangPagination']);

    Route::post('rack', [RoomController::class, 'roomRack']);
    Route::post('towerRoomRack', [RoomController::class, 'towerRoomRack']);
    Route::get('get-sort-order', [RoomController::class, 'Get_MaxSort_order']);
    Route::get('get-aval-dummy-room', [RoomController::class, 'getAvalDummyRoom']);
    Route::post('folio_statement', [FolioStatementController::class, 'Folio_Statement']);

    Route::post('Floor_by_rooms', [FloorController::class, 'Floor_by_rooms']);
    Route::apiResource('dayCloseRecord', DayCloseRecordController::class);
    Route::post('countFoStatus', [DayCloseRecordController::class, 'countFoStatus']);
    Route::post('getCheckInRooms', [DayCloseRecordController::class, 'getCheckInRooms']);
    Route::post('getCheckOutRooms', [DayCloseRecordController::class, 'getCheckOutRooms']);
    Route::post('getReservisionCancel', [DayCloseRecordController::class, 'getReservisionCancel']);
    Route::post('getReservisionToday', [DayCloseRecordController::class, 'getReservisionToday']);
    Route::post('guestLedger', [DayCloseRecordController::class, 'guestLedger']);
    Route::get('getAllRoom', [DayCloseRecordController::class, 'getAllRoom']);
    Route::post('sumOfCheckInPax', [DayCloseRecordController::class, 'sumOfCheckInPax']);
    Route::post('sumOfCheckOutPax', [DayCloseRecordController::class, 'sumOfCheckOutPax']);
    Route::post('getCountOfGroup', [DayCloseRecordController::class, 'getCountOfGroup']);
    Route::post('dayUseRoom', [DayCloseRecordController::class, 'dayUseRoom']);
    Route::post('dayUseRoomCount', [DayCloseRecordController::class, 'dayUseRoomCount']);
    Route::post('expectedtomorrowCount', [DayCloseRecordController::class, 'expectedtomorrowCount']);
    Route::post('expectedtomorrowSumPax', [DayCloseRecordController::class, 'expectedtomorrowSumPax']);
    Route::post('expectedOuttomorrowRoomsCount', [DayCloseRecordController::class, 'expectedOuttomorrowRoomsCount']);
    Route::post('guestInhousPax', [DayCloseRecordController::class, 'guestInhousPax']);
    Route::post('dayUseSumOfPax', [DayCloseRecordController::class, 'dayUseSumOfPax']);
    Route::post('expectedOuttomorrowSumPax', [DayCloseRecordController::class, 'expectedOuttomorrowSumPax']);
    Route::post('totalFbRateromeOthersIndrate', [DayCloseRecordController::class, 'totalFbRateromeOthersIndrate']);
    Route::post('totalPaymants', [DayCloseRecordController::class, 'totalPaymants']);

    //start wafa
    Route::post('getCountOfCompOrHousRooms', [DayCloseRecordController::class, 'getCountOfCompOrHousRooms']);
    Route::get('Calc_Of_AR_Deposit_Payment', [DayCloseRecordController::class, 'Calc_Of_AR_Deposit_Payment']);
    Route::get('Calc_Of_AR_Balance', [DayCloseRecordController::class, 'Calc_Of_AR_Balance']);
    Route::post('Count_Of_Extended_Rooms', [DayCloseRecordController::class, 'Count_Of_Extended_Rooms']);
    Route::post('Sum_Of_Extended_Pax', [DayCloseRecordController::class, 'Sum_Of_Extended_Pax']);
    Route::post('Sum_Of_Total_Taxes', [DayCloseRecordController::class, 'Sum_Of_Total_Taxes']);
    //end wafa

    Route::apiResource('dayClose', DayCloseController::class);
    Route::get('Move_Guest_To_History', [DayCloseOutController::class, 'Move_Guest_To_History']);




    Route::apiResource('chargeRout', ChargeRoutedController::class);
    Route::post('storechargeRoutwithoutStatus', [ChargeRoutedController::class, 'storechargeRoutwithoutStatus']);

    Route::apiResource('apiKey', ApiKeyController::class);
    Route::post('activeApiKey', [ApiKeyController::class, 'activateApiKey']);
    Route::post('deActivateApiKey', [ApiKeyController::class, 'deActivateApiKey']);

    Route::apiResource('notification', NotificationController::class);
    Route::get('getLast5Notification', [NotificationController::class, 'getLast5']);

    //Route::get('test', [TestController::class, 'test'])->middleware('auth.apikey');
});

Route::post('test', [TestController::class, 'test']);

Route::apiResource('JournalVoucherType', JournalVoucherTypeController::class);

Route::middleware('tenant')->group(function () {
    // routes
});
