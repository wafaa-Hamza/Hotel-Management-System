<?php

namespace App\Repository\profiles\guestProfile;

use App\helpers;
use Carbon\Carbon;
use App\Models\Room;
use App\Models\Guests;
use App\Models\Window;
use App\Models\Setting;
use App\Models\MoreName;
use App\Models\RoomType;
use App\Models\preCharge;
use App\Models\ResRateDay;
use App\Models\RoomStatus;
use App\Models\TaskDetail;
use App\Models\OordService;
use App\Models\Payment_type;
use Illuminate\Http\Request;
use App\Models\guest_profile;
use App\Models\GuestAttachment;
use App\Models\RoomStatusHistory;
use App\Traits\HandelDateToHeugry;
use Spatie\Multitenancy\Models\Tenant;
use App\Repositoryinterface\Generalinterface;
use App\Http\Controllers\TransactionController;
use App\Jobs\ProcessRepositoryNTMPCreateOrUpdate;
use App\Http\Controllers\CompanyStatementController;
use App\Http\Controllers\RoomStatusHistoryController;
use App\Models\GroupDetail;
use App\Repositoryinterface\Integration\ShomoosInterface;
use App\Repositoryinterface\RepositoryTaskDetailsinterface;
use App\Repositoryinterface\Integration\IntegrationForNTInterface;
use App\Repositoryinterface\Integration\ShomoosIntegrationInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryWindowInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryInvoiceInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryPreChargeInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryChargeRoutedInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryGuestInhouseInterface;

class DBrepositoryGuestInhouse implements RepositoryGuestInhouseInterface
{

    use helpers,HandelDateToHeugry;
    private $guestInhouseModel;
    private $roomModel;
    private $roomTypeModel;
    private $OordServices;
    private $resRateDays;
    private $preChargeModel;
    private $moreNameModel;
    private $guestProfileModel;
    private $windowModel;
    private $tenantModel;
    private $guestAttachmentModel;
    private $settingModel;
    private $payMentTypeModel;
    private $roomStatusHistoryModel;
    private $taskDetailModel;
    private $groupDetailModel;

    private $repositoryWindowInterface;
    private $repositoryChargeRoutedInterface;
    private $generalinterface;
    private $invoiceInterface;
    private $transactionController;
    private $companyStatementController;
    private $roomStatusHistoryController;
    private $integrationForNTInterface;
    private $taskDetailInterface;
    private $preChargeInterface;
    private $shomoosInterface;
    private $ShomoosIntegrationInterface;
    public function __construct(
        RepositoryInvoiceInterface $invoiceInterface,
        Generalinterface $generalinterface,
        RepositoryChargeRoutedInterface $repositoryChargeRoutedInterface,
        RepositoryWindowInterface $repositoryWindowInterface,
        Guests $guestInhouseModel,
        Room $roomModel,
        RoomType $roomTypeModel,
        OordService $OordServices,
        ResRateDay $resRateDays,
        preCharge $preChargeModel,
        MoreName $moreNameModel,
        guest_profile $guestProfileModel,
        Window $windowModel,
        Tenant $tenantModel,
        GuestAttachment $guestAttachmentModel,
        Setting $settingModel,
        Payment_type $payMentTypeModel,
        TaskDetail $taskDetailModel,
        GroupDetail $groupDetailModel,
        TransactionController $transactionController,
        CompanyStatementController $companyStatementController,
        RoomStatusHistory $roomStatusHistoryModel,
        RoomStatusHistoryController $roomStatusHistoryController,
        IntegrationForNTInterface $integrationForNTInterface,
        RepositoryTaskDetailsinterface $taskDetailInterface,
        RepositoryPreChargeInterface $preChargeInterface,
        ShomoosInterface $shomoosInterface,
        ShomoosIntegrationInterface $ShomoosIntegrationInterface
    ) {
        $this->guestInhouseModel = $guestInhouseModel;
        $this->roomModel = $roomModel;
        $this->roomTypeModel = $roomTypeModel;
        $this->OordServices = $OordServices;
        $this->resRateDays = $resRateDays;
        $this->moreNameModel = $moreNameModel;
        $this->guestProfileModel = $guestProfileModel;
        $this->windowModel = $windowModel;
        $this->repositoryWindowInterface = $repositoryWindowInterface;
        $this->repositoryChargeRoutedInterface = $repositoryChargeRoutedInterface;
        $this->generalinterface = $generalinterface;
        $this->invoiceInterface = $invoiceInterface;
        $this->preChargeModel = $preChargeModel;
        $this->tenantModel = $tenantModel;
        $this->guestAttachmentModel = $guestAttachmentModel;
        $this->roomStatusHistoryModel = $roomStatusHistoryModel;
        $this->settingModel = $settingModel;
        $this->taskDetailModel = $taskDetailModel;
        $this->groupDetailModel = $groupDetailModel;

        $this->transactionController = $transactionController;
        $this->payMentTypeModel = $payMentTypeModel;
        $this->companyStatementController = $companyStatementController;
        $this->roomStatusHistoryController = $roomStatusHistoryController;
        $this->integrationForNTInterface = $integrationForNTInterface;
        $this->taskDetailInterface = $taskDetailInterface;
        $this->preChargeInterface = $preChargeInterface;
        $this->shomoosInterface = $shomoosInterface;
        $this->ShomoosIntegrationInterface = $ShomoosIntegrationInterface;
    }

    public function index()
    { 
        $guestInhouse = $this->guestInhouseModel->with(
            'profile',
            //   'company',
            'room.room_type',
            'rateCode.meal',
            'rateCode.meal_package',
            'rateCode.roomTypes',
            'marketSegment',
            'sourceBusiness',
            'meal',
            'createdBy',
            'resRateDay.rateCode',
        )->get();
        return $guestInhouse;
    }
    public function inhousPagination()
    {
        $guestInhouse = $this->guestInhouseModel->with(
            'profile',
            'room.room_type',
            'roomType',
            'rateCode.meal',
            'rateCode.meal_package',
            'rateCode.roomTypes',
            'marketSegment',
            'sourceBusiness',
            'meal',
            'createdBy',
        )->paginate(request()->segment(count(request()->segments())));
        return $guestInhouse;
    }

    public function getGuestInhous($request)
    {
        $hotelData = $this->settings()->first()->hotel_date;
        $guestInHous = $this->guestInhouseModel::search($request->term, null, $request->mixedSearch, $request->spaceficSearch)

            ->where('is_checked_in', 1)
            ->orWhere(function ($q) use ($hotelData) {
                $q->where('is_checked_in', 0)
                    ->where('checkout_at', $hotelData);
            })
            ->get();
        return $guestInHous;
    }
    public function getGuestInhouswithDates($request)
    {

        $hotelData = $this->settings()->first()->hotel_date;
        $guestInHous = $this->guestInhouseModel::where(function ($q) use ($request) {
            if ($request->id != '') {
                $q->where('id', $request->id);
            }
            if ($request->ref_no != '') {
                $q->where('ref_no', $request->ref_no);
            }
            if ($request->guest_name != '') {
                $q->with('profile', (function ($q2) use ($request) {
                    $q2->whereLike('first_name', "%$request->guest_name%");
                    $q2->orWhereLike('last_name', "%$request->guest_name%");
                }));
            }
            if ($request->room_type_id != '') {
                $q->where('room_type_id', $request->room_type_id);
            }
            if ($request->source_of_business_id != '') {
                $q->where('source_of_business_id', $request->source_of_business_id);
            }
            if ($request->market_segment_id != '') {
                $q->where('market_segment_id', $request->market_segment_id);
            }
            if ($request->company_id != '') {
                $q->where('company_id', $request->company_id);
            }
            if ($request->rate_code_id != '') {
                $q->where('rate_code_id', $request->rate_code_id);
            }
            if ($request->checked_out != '') {
                $q->where('checked_out', $request->checked_out);
            }

            if ($request->has('InDate')) {
                if ($request->InDate != '') {
                    $q->whereDate('in_date', '>=', $request->start_date);
                    $q->whereDate('in_date', '<=', $request->end_date);
                }
            }
            if ($request->has('OutDate')) {
                if ($request->OutDate != '') {
                    $q->whereDate('out_date', '>=', $request->start_date);
                    $q->whereDate('out_date', '<=', $request->end_date);
                }
            }
        })

            ->where('is_checked_in', 1)
            ->orWhere(function ($q) use ($hotelData) {
                $q->where('is_checked_in', 0)
                    ->where('checkout_at', $hotelData);
            })
            ->get();
        return $guestInHous;
    }

    public function changeRoomStatus($room_id, $fo_status, $description, $hk_status = null)
    {
        if ($room_id && $fo_status && $description) {

            $roomData =  $this->roomModel::where('id', $room_id)->first();

            $oldFOStatus = $roomData->fo_status;
            $oldHKStatus = $roomData->hk_stauts;

            $hk_statusData = null;
            if ($hk_status == null) {
                $hk_statusData = $oldHKStatus;
            } else {
                $hk_statusData = $hk_status;
            }

            $status = RoomStatus::where('status_code', $fo_status . $hk_statusData)->first();
            if (!$status) {
                $status = RoomStatus::where('status_code', $fo_status)->first();
            }
            $updateStatus =  $roomData->Update([
                'fo_status' => $fo_status,
                'hk_stauts' => $hk_statusData,
                'room_statuse_id' => $status->id,
            ]);

            $this->roomStatusHistoryController->InsertStatusChange(
                $room_id,
                $description,
                $oldFOStatus,
                $fo_status,
                $oldHKStatus,
                $hk_statusData
            );

            return  $updateStatus;
        } else {
            return false;
        }
    }

    public function store($request)
    {
        if($request->has('is_dummy') && $request->is_dummy ==1 && empty($request->room_type_id))
        {
           $dumyRoomTypeID = $this->roomTypeModel->where('rm_type_code','DUM')->select('id')->first();
        }
        $guest = $this->guestInhouseModel::create(
            [
                'folio_no' => $request->folio_no,
                'in_date' => $request->in_date,
                'out_date' => (!empty($request->out_date)) ? $request->out_date : $request->in_date,
                'original_out_date' => $request->original_out_date,
                'no_of_nights' => $request->no_of_nights,
                'rm_rate' =>($request->has('is_dummy') && $request->is_dummy ==1 && empty($request->room_type_id)) ? '0' : $request->rm_rate,
                'taxes' => $request->taxes,
                'no_of_pax' =>($request->has('is_dummy') && $request->is_dummy ==1 && empty($request->room_type_id)) ? '0' : $request->no_of_pax,
                'hotel_note' => $request->hotel_note,
                'client_note' => $request->client_note,
                'way_of_payment' =>($request->has('is_dummy') && $request->is_dummy ==1 && empty($request->room_type_id)) ? 'dummy' : $request->way_of_payment,
                'profile_id' => $request->profile_id,
                'company_id' => $request->company_id,
                'room_id' => $request->room_id,
                //'room_type_id' => $request->room_type_id,
                'room_type_id' =>($request->has('is_dummy') && $request->is_dummy ==1 && empty($request->room_type_id)) ? $dumyRoomTypeID->id : $request->room_type_id,
                'rate_code_id' => $request->rate_code_id,
                'market_segment_id' =>($request->has('is_dummy') && $request->is_dummy ==1 && empty($request->room_type_id)) ? '1' : $request->market_segment_id,
                'source_of_business_id' =>($request->has('is_dummy') && $request->is_dummy ==1 && empty($request->room_type_id)) ? '1' : $request->source_of_business_id,
                'meal_id' => $request->meal_id,
                'meal_package_id' => $request->meal_package_id,
                'created_by' => auth()->id(),
                'created_inhousGuest_at' => $request->created_inhousGuest_at,
                'checked_out' => 0,
                'checkout_by' => $request->checkout_by,
                'checkout_at' => $request->checkout_at,
                'is_reservation' => (!empty($request->is_reservation)) ? $request->is_reservation : 0,
                'res_status' =>($request->has('is_dummy') && $request->is_dummy ==1 && empty($request->room_type_id)) ? 'CNF' : $request->res_status,
                'is_group' => (!empty($request->is_group)) ? $request->is_group : 0,
                'group_code' => $request->group_code,
                'is_dummy' => (!empty($request->is_dummy)) ? $request->is_dummy : 0,
                'Is_PM' => (!empty($request->Is_PM)) ? $request->Is_PM : 0,
                'res_no' => $request->res_no,
                'ref_no' => $request->ref_no,
                'vip' => $request->vip,
                'vat_no' => $request->vat_no,
                'company_name' => $request->company_name,
                'inv_address' => $request->inv_address,
                'res_type' => ($request->has('res_type')) ? $request->res_type : null,
                'scth_transaction_id' => ($request->has('scth_transaction_id')) ? $request->scth_transaction_id : null,
                'customer_type' =>($request->has('is_dummy') && $request->is_dummy ==1 && empty($request->room_type_id)) ? '1' : $request->customerType,
                'purpose_of_visit' =>($request->has('is_dummy') && $request->is_dummy ==1 && empty($request->room_type_id)) ? '1' : $request->purposeOfVisit,

                'no_of_child' => (!empty($request->no_of_child)) ? $request->no_of_child : 0,
                'no_of_inf' =>(!empty($request->no_of_inf)) ? $request->no_of_inf : 0,
 
            ]
        );

        $updateResNoRequest = new Request;
        $resNoData = ['res_no' => $guest->id];
        $updateResNoRequest->merge($resNoData);
        $updateResNo = $this->update($updateResNoRequest, $guest->id);

        if ($request->has('file') && $guest->id) {
            $AttachPreveosData = $this->guestAttachmentModel->where('guest_id', $guest->id)->latest()->first();
            $arr = [];
            $arr['guest_id'] = $guest->id;
            $arr['file'] = $request->file;
            $attachRequest = new Request;
            $attachRequest->merge($arr);
            $this->guestAttachment($attachRequest);
        }

        //start creat window for this room
        $windowRequest =
            [
                'window_name' => 'window for' . $guest->id,
                'guest_id' =>  $guest->id,

            ];

        $windowNumber = $this->windowModel::latest()->max('id') + 1;

        $windowData = $this->repositoryWindowInterface->store($windowRequest, null, $windowNumber);
        //end create window
        $guestInhouse =  $this->guestInhouseModel::where('id', $guest->id)->with(
            'profile',
            //   'company',
            'attachment',
            'moreName',
            'room',
            'roomType',
            'rateCode',
            'marketSegment',
            'sourceBusiness',
            'meal',
            'createdBy'
        )->get();
        if ($guestInhouse->first()->room_id != null) {
            $this->changeRoomStatus($guestInhouse->first()->room_id, 'BL', 'Change By Store Reservation');
        }
            if($this->settings()->first()->ntmp_active)
            {
                if($request->is_dummy != 1){
                    $ntmp = $this->integrationForNTInterface->NTMPCreateOrUpdate($guest->id, 1, 1);
                    // $integrationForNTInterfaceCreate = app()->make($this->integrationForNTInterface::class); 
                    // ProcessRepositoryNTMPCreateOrUpdate::dispatch($integrationForNTInterfaceCreate, $guest->id);
                    $this->guestInhouseModel->where('id', $guest->id)->update([
                        'scth_transaction_id' => $ntmp['transactionId']// need check
                    ]);
                }
              
            }


   

        return  $guestInhouse;
    }

    public function storeMoreName($requests, $guest_id)
    {
        if (!empty($requests->onlyNameData)) {
            $requests = $requests->onlyNameData[0];
            $moreName = $this->moreNameModel::create([
                'guest_id' => (!empty($requests['guest_id'])) ? $requests['guest_id'] : $guest_id['guest_id'],
                'profile_id' => (!empty($requests['profile_id'])) ? $requests['profile_id'] : 1,
                'country_id' => (!empty($requests['country_id'])) ? $requests['country_id'] : null,
                'first_name' => (!empty($requests['first_name'])) ? $requests['first_name'] : null,
                'last_name' => (!empty($requests['last_name'])) ? $requests['last_name'] : null,
                'email' => (!empty($requests['email'])) ? $requests['email'] : null,
                'id_no' => (!empty($requests['id_no'])) ? $requests['id_no'] : null,
                'mobile' => (!empty($requests['mobile'])) ? $requests['mobile'] : null,
                'birth_date' => (!empty($requests['birth_date'])) ? $requests['birth_date'] : null,
                'vip' => (!empty($requests['vip'])) ? $requests['vip'] : null,
            ]);
        } else {
            foreach ($requests as $request) {
                $moreName = $this->moreNameModel::create([
                    'guest_id' => (!empty($request['guest_id'])) ? $request['guest_id'] : $guest_id['guest_id'],
                    'profile_id' => (!empty($request['profile_id']) && $request['profile_id'] != 1) ? $request['profile_id'] : null,
                    'country_id' => (!empty($request['country_id'])) ? $request['country_id'] : null,
                    'first_name' => (!empty($request['first_name'])) ? $request['first_name'] : null,
                    'last_name' => (!empty($request['last_name'])) ? $request['last_name'] : null,
                    'email' => (!empty($request['email'])) ? $request['email'] : null,
                    'id_no' => (!empty($request['id_no'])) ? $request['id_no'] : null,
                    'mobile' => (!empty($request['mobile'])) ? $request['mobile'] : null,
                    'birth_date' => (!empty($request['birth_date'])) ? $request['birth_date'] : null,
                    'vip' => (!empty($requests['vip'])) ? $requests['vip'] : null,
                ]);
            }
        }

        if (!empty($request['guest_id'])) {
            $moreName = $this->moreNameModel::where('guest_id', $request['guest_id'])->with('profile', 'country', 'guest')->get();
        } else {
            $moreName = $this->moreNameModel::where('guest_id', $guest_id['guest_id'])->with('profile', 'country', 'guest')->get();
        }

        return $moreName;
    }

    public function storeOnlyAndMoreName($request, $guest_id)
    {
        $requests = $request->onlyNameData[0];
        $moreName = $this->moreNameModel::create([
            'guest_id' => (!empty($requests['guest_id'])) ? $requests['guest_id'] : $guest_id['guest_id'],
            'profile_id' => (!empty($requests['profile_id'])) ? $requests['profile_id'] : 1,
            'country_id' => (!empty($requests['country_id'])) ? $requests['country_id'] : null,
            'first_name' => (!empty($requests['first_name'])) ? $requests['first_name'] : null,
            'last_name' => (!empty($requests['last_name'])) ? $requests['last_name'] : null,
            'email' => (!empty($requests['email'])) ? $requests['email'] : null,
            'id_no' => (!empty($requests['id_no'])) ? $requests['id_no'] : null,
            'mobile' => (!empty($requests['mobile'])) ? $requests['mobile'] : null,
            'birth_date' => (!empty($requests['birth_date'])) ? $requests['birth_date'] : null,
        ]);
        $requestData = $request->moreNameData;
        foreach ($requestData as $requestMoreName) {
            // dd($request);
            $moreName = $this->moreNameModel::create([
                'guest_id' => (!empty($moreNameModel['guest_id'])) ? $requestMoreName['guest_id'] : $guest_id['guest_id'],
                'profile_id' => (!empty($requestMoreName['profile_id'])  && $requestMoreName['profile_id'] != 1) ? $requestMoreName['profile_id'] : null,
                'country_id' => (!empty($requestMoreName['country_id'])) ? $requestMoreName['country_id'] : null,
                'first_name' => (!empty($requestMoreName['first_name'])) ? $requestMoreName['first_name'] : null,
                'last_name' => (!empty($requestMoreName['last_name'])) ? $requestMoreName['last_name'] : null,
                'email' => (!empty($requestMoreName['email'])) ? $requestMoreName['email'] : null,
                'id_no' => (!empty($requestMoreName['id_no'])) ? $requestMoreName['id_no'] : null,
                'mobile' => (!empty($requestMoreName['mobile'])) ? $requestMoreName['mobile'] : null,
                'birth_date' => (!empty($requestMoreName['birth_date'])) ? $requestMoreName['birth_date'] : null,
            ]);
        }
    }

    public function deleteMoreName($guest_id)
    {
        $noreNameData = $this->moreNameModel::where('guest_id', $guest_id)->where(function ($q) {
            $q->where('profile_id', '!=', 1)->orWhere('profile_id', null);
        })->get();
        if ($noreNameData->first()) {
            foreach ($noreNameData as $data) {
                $data->delete();
            }
            return 'done';
        } else {
            return null;
        }
    }
    public function deleteOnlyName($guest_id)
    {
        $noreNameData = $this->moreNameModel::where('guest_id', $guest_id)->where(function ($q) {
            $q->where('profile_id', '=', 1);
        })->get();
        if ($noreNameData->first()) {
            foreach ($noreNameData as $data) {
                $data->delete();
            }
            return 'done';
        } else {
            return null;
        }
    }

    public function resRateDaysStore($request, $guestId)
    {
        $guestresType = $this->guestInhouseModel::where('id', $guestId)->select('res_type')->first()->res_type;
        if ($request->res_rate_days && $guestresType == null || $guestresType == 'D') {
            foreach ($request->res_rate_days as $resRateDay) {
                $create =  $this->resRateDays::updateOrCreate(
                    [
                        'day_date' => $resRateDay['day_date'],
                        'guest_id' => $guestId
                    ],
                    [
                        'guest_id' => $guestId,
                        'day_date' => $resRateDay['day_date'],
                        'rm_rate' => $resRateDay['rm_rate'],
                        'rate_id' => $resRateDay['rate_id'],
                        'meal_id' => $resRateDay['meal_id'],
                        'meal_package_id' => $resRateDay['meal_package_id'],
                    ]
                );
            }
            $resRateDayData = $this->guestInhouseModel::where('id', $guestId)
                ->with('resRateDay.rateCode', 'room')
                ->get();

            return $resRateDayData;
        } elseif ($request->res_rate_days && $guestresType == 'M') {
            $inDate = Carbon::parse($request->in_date);
            $outDate = Carbon::parse($request->out_date)->subDay();
            for ($date = $inDate; $date->lte($outDate); $date->addMonth()) {
                //dd($request->res_rate_days[0]['meal_package_id']);
                $create =  $this->resRateDays::create([
                    'guest_id' => $guestId,
                    'day_date' => $date,
                    'rm_rate' => $request->rm_rate,
                    'rate_id' => (!empty($request->rate_code_id)) ? $request->rate_code_id : null,
                    'meal_id' => (!empty($request->res_rate_days[0]['meal_id'])) ? $request->res_rate_days[0]['meal_id'] : null,
                    'meal_package_id' => (!empty($request->res_rate_days[0]['meal_package_id'])) ? $request->res_rate_days[0]['meal_package_id'] : null,
                ]);
            }
            $resRateDayData = $this->guestInhouseModel::where('id', $guestId)->with('resRateDay.rateCode', 'room')->get();
            return $resRateDayData;
        } elseif ($request->res_rate_days && $guestresType == 'Y') {
            $inDate = Carbon::parse($request->in_date);
            $outDate = Carbon::parse($request->out_date)->subDay();
            for ($date = $inDate; $date->lte($outDate); $date->addYear()) {
                $create =  $this->resRateDays::create([
                    'guest_id' => $guestId,
                    'day_date' => $date,
                    'rm_rate' => $request->rm_rate,
                    'rate_id' => (!empty($request->rate_code_id)) ? $request->rate_code_id : null,
                    'meal_id' => (!empty($request->meal_id)) ? $request->meal_id : null,
                    'meal_package_id' => (!empty($request->meal_package)) ? $request->meal_package : null,
                ]);
            }
            $resRateDayData = $this->guestInhouseModel::where('id', $guestId)->with('resRateDay.rateCode', 'room')->get();
            return $resRateDayData;
        } else {
            $inDate = Carbon::parse($request->in_date);
            $outDate = Carbon::parse($request->out_date)->subDay();
            for ($date = $inDate; $date->lte($outDate); $date->addDay()) {
                $create =  $this->resRateDays::create([
                    'guest_id' => $guestId,
                    'day_date' => $date,
                    'rm_rate' => $request->rm_rate,
                    'rate_id' => (!empty($request->rate_code_id)) ? $request->rate_code_id : null,
                    'meal_id' => (!empty($request->meal_id)) ? $request->meal_id : null,
                    'meal_package_id' => (!empty($request->meal_package)) ? $request->meal_package : null,
                ]);
            }
            $resRateDayData = $this->guestInhouseModel::where('id', $guestId)->with('resRateDay.rateCode', 'room')->get();
            return $resRateDayData;
        }
    }
    public function resRateDaysDelete($id)
    {
        $reservisionData = $this->resRateDays::where('guest_id', $id)->get();
        foreach ($reservisionData as $reservision) {
            $reservision->delete();
        }
    }

    public function lockedReservision($id, $request)
    {
        $guestCheck = $this->guestInhouseModel::where('id', $id)->first();
        if ($guestCheck) {
            $lockedReservision = $this->guestInhouseModel::where('id', $id)->update([
                'locked' => $request->lock
            ]);

            return $lockedReservision;
        } else {
            return false;
        }
    }

    /**
     * group reservision functions 
     *
     * @functions
     * isGroupProfileExist
     * storeGroupProfile
     * getMaxOfGroupCode
     * storeGroupGuest
     * updateAsPayMasterGuest
     * updateGroupGuest
     * showGroupRservision
     * destroy

     */
    //start groupReservision
    public function isGroupProfileExist($id_no)
    {

        // dd();
        $groupProfile = $this->guestProfileModel::where('id_no', $id_no)
            // ->whereHas('guest')
            ->whereHas('guest', function ($q) {
                $q->where('checked_out', 1);
            })
            ->first();
        return $groupProfile;
    }

    public function storeGroupProfile($request)
    {

        $groupProfileExist = $this->isGroupProfileExist($request->id_no);
        if ($groupProfileExist) {
            $groupCoud = $this->getMaxOfGroupCode();
            // dd( $groupCoud);
            $groupProfile = $this->guestProfileModel::where('mobile', $request->mobile)->update([
                'group_code' => $groupCoud
            ]);
            $groupProfileData = $this->guestProfileModel::where('mobile', $request->mobile)->get();
            return $groupProfileData;
        } else {
            $groupCoud = $this->getMaxOfGroupCode();
            $groupProfile = $this->guestProfileModel::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'id_no' => $groupCoud,
                'group_code' => $groupCoud,
                'Profile_no' => 'G-' . $groupCoud,
                'mobile' => $request->mobile,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'language' => $request->language,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'country_id' => $request->country_id,
            ]);

            $groupProfileData = $this->guestProfileModel::where('group_code', $groupCoud)->get();
            return $groupProfileData;
        }


        // dd('done');

    }

    public function getMaxOfGroupCode()
    {

        $groupCoud = $this->guestProfileModel->max('group_code');
        if ($groupCoud == null) {
            $groupCoud = 1;
        } else {
            $groupCoud = $groupCoud + 1;
        }
        return $groupCoud;
    }
    public function getMaxOfGroupCodeFromGuest()
    {

        $groupCoud = $this->guestInhouseModel->max('group_code');
        //dd($groupCoud);
        if ($groupCoud == null) {
            $groupCoud = 10000;
        } else {
            $groupCoud = $groupCoud + 1;
        }
        return $groupCoud;
    }

    public function storeGroupGuest($request)
    {

        $count = 0;
        if(!$request->has('groupName'))
        {
            $groupProfile = $this->guestProfileModel::where('mobile', $request->mobile)->orWhere('id', $request->profile_id)->first();
        }else{
            $groupProfile = $this->guestProfileModel::where('first_name', 'group_paymaster')->first();
        }
        $payMasterRoomType = $this->roomTypeModel::where('rm_type_code', 'GPM')->first('id');
        $lastWindow = $this->windowModel::latest()->max('id');
        //dd($groupProfile);
        if ($groupProfile) {
            $groupCode = $this->getMaxOfGroupCodeFromGuest();
            $payMasterGuest = $this->guestInhouseModel::create(
                [
                    'folio_no' => null,
                    'in_date' => $request->in_date,
                    'out_date' => (!empty($request->out_date)) ? $request->out_date : $request->in_date,
                    'original_out_date' => $request->original_out_date,
                    'no_of_nights' => $request->no_of_nights,
                    'rm_rate' => 0,
                    'taxes' => $request->taxes,
                    'no_of_pax' => $request->no_of_pax,
                    'hotel_note' => $request->hotel_note,
                    'client_note' => $request->client_note,
                    'way_of_payment' => $request->way_of_payment,
                    'profile_id' => $groupProfile->id,
                    'company_id' => $request->company_id,
                    'room_id' => null,
                    'room_type_id' => $payMasterRoomType->id,
                    'rate_code_id' => $request->rate_code_id,
                    'market_segment_id' => $request->market_segment_id,
                    'source_of_business_id' => $request->source_of_business_id,
                    'meal_id' => null,
                    'meal_package_id' => null,
                    'created_by' => auth()->id(),
                    'created_inhousGuest_at' => $request->created_inhousGuest_at,
                    'checked_out' => $request->checked_out,
                    'checkout_by' => 0,
                    'checkout_at' => $request->checkout_at,
                    'is_reservation' => 1,
                    'res_status' => $request->res_status,
                    'is_group' => 1,
                    'group_code' => $groupCode,
                    'is_dummy' => 0,
                    'Is_PM' => 0,
                    'res_no' => $request->res_no,
                    'ref_no' => $request->ref_no,
                    'vip' => $request->vip,
                    'scth_transaction_id' => ($request->has('res_type')) ? $request->res_type : null,
                    'customer_type' => $request->customerType,
                    'purpose_of_visit' => $request->purposeOfVisit,
                ]
            );

            if($request->has('groupName'))
        {
            $this->storeGroupDetail($request,$payMasterGuest->id);
        }
            // dd( $payMasterGuest->id);
            $payMasterID = $payMasterGuest->id;
            $updateResNoData = [
                "guest_id" => [$payMasterID],
                'res_no' => $payMasterID
            ];
            $updatePayMasterStatus = $this->updateOneInGroupGuest($updateResNoData);
            //start window for payMaster
            $windowPayMasterRequest =
                [
                    'window_name' => 'master window',
                    'guest_id' => $payMasterGuest->id,

                ];
            if ($lastWindow) {

                $payMasterWindow = $this->repositoryWindowInterface->store($windowPayMasterRequest, null, $lastWindow);
            } else {
                $payMasterWindow = $this->repositoryWindowInterface->store($windowPayMasterRequest, null, null);
            }

            //end window for payMaster


            //end create payMaster Guest Group

            foreach ($request->rooms as $room) {
                $guest = $this->guestInhouseModel::create(
                    [
                        'folio_no' => $request->folio_no,
                        'in_date' => $request->in_date,
                        'out_date' => (!empty($request->out_date)) ? $request->out_date : $request->in_date,
                        'original_out_date' => $request->original_out_date,
                        'no_of_nights' => $request->no_of_nights,
                        'rm_rate' => $room['rm_rate'],
                        'taxes' => $request->taxes,
                        'no_of_pax' => $room['no_pax'],
                        'hotel_note' => $request->hotel_note,
                        'client_note' => $request->client_note,
                        'way_of_payment' => $request->way_of_payment,
                        'profile_id' => $groupProfile->id,
                        'company_id' => $request->company_id,
                        'room_id' => (!empty($room['room_id'])) ? $room['room_id'] : null,
                        'room_type_id' => $room['roomType'],
                        'rate_code_id' => (!empty($room['rate_code_id'])) ? $room['rate_code_id'] : null,
                        'market_segment_id' => $request->market_segment_id,
                        'source_of_business_id' => $request->source_of_business_id,
                        'meal_id' => $request->meal_id,
                        'meal_package_id' => $request->meal_package_id,
                        'created_by' => auth()->id(),
                        'created_inhousGuest_at' => $request->created_inhousGuest_at,
                        'checked_out' => $request->checked_out,
                        'checkout_by' => 0,
                        'checkout_at' => $request->checkout_at,
                        'is_reservation' => 1,
                        'res_status' => $request->res_status,
                        'is_group' => 1,
                        'group_code' => $groupCode,
                        'is_dummy' => 0,
                        'Is_PM' => 0,
                        'res_no' => $payMasterID,
                        'ref_no' => $request->ref_no,
                        'vip' => $request->vip,
                        'customer_type' => $request->customerType,
                        'scth_transaction_id' => $request->scth_transaction_id,
                        'purpose_of_visit' => $request->purposeOfVisit,
                    ]
                );

                if (array_key_exists('res_rate_days', $room))
                {
                    $requestData =new Request;
                    $requestData->merge($room);
                    $guestInhouseWithResRateDays = $this->resRateDaysStore($requestData, $guest->id); 
                    $getPrechargeData = $this->preChargeInterface->getPreChargeDAtaForGest($guest->id);
                    $guestInhouseAddPrecharge = $this->preChargeInterface->preChargeStore($getPrechargeData);
                }
                // dd($room);
if($this->settings()->first()->ntmp_active) {
    $ntmp = $this->integrationForNTInterface->NTMPCreateOrUpdate($guest->id, 1);
}
            }

            //start creat window for this room
            $windowRequest =
                [
                    'window_name' => 'window for' . $guest->id,
                    'guest_id' =>  $guest->id,

                ];

            if ($count == 0) {
                $windowNumber = $payMasterWindow->first()->id;
            }
            $count = 1;

            $windowData = $this->repositoryWindowInterface->store($windowRequest, null, $windowNumber);
            $windowNumber++;

            $chargeRoutedRequest = [
                'window_id_from' => $windowData->first()->window_number,
                'window_id_to' => $payMasterWindow->first()->id,
                'guest_id_from' => $guest->id,
                'guest_id_to' => $payMasterGuest->id,
            ];

            $chargeRouted = $this->repositoryChargeRoutedInterface->storeDefualtChargeRouted($chargeRoutedRequest);
            //end create chargeRouted






            //     $guest = $this->guestInhouseModel::where('group_code',$groupProfile->group_code)->with('profile')->get('id');
            // return $guest;

            return  $payMasterGuest->id;
        } else {

            return null;
        }
    }

    public function storeGroupDetail($request,$guest_id)
    {
      $groupDetial =  $this->groupDetailModel->create([
            'name' => $request->groupName,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'guest_id' => $guest_id,
        ]);
        return $groupDetial;
    }

    public function updateAsPayMasterGuest($request)
    {
        $updatePayMasterStatus = $this->updateOneInGroupGuest($request);
        if ($updatePayMasterStatus) {
            $requestForChiledGuestGroup = collect($request)->except(['room_type_id', 'rate_code_id', 'no_of_pax', 'no_of_nights', 'room_id', 'rm_rate'])->toArray();
            // dd( $requestForChiledGuestGroup);
            $allgroup = $this->guestInhouseModel::where('group_code', $request['group_code'])->where('room_type_id', '!=', 1)->get();
            foreach ($allgroup as $oneGuetInGroup) {
                $updatePayMasterStatus = $this->updateOneInGroupGuest($requestForChiledGuestGroup, $oneGuetInGroup->id);
            }
        }
        return 1;
    }

    public function addGuestInGroup($request)
    {
        $guest = $this->guestInhouseModel::create(
            [
                'folio_no' => $request['folio_no'],
                'in_date' => $request['in_date'],
                'out_date' => (!empty($request['out_date'])) ? $request['out_date'] : $request['in_date'],
                'original_out_date' => $request['original_out_date'],
                'no_of_nights' => $request['no_of_nights'],
                'rm_rate' => $request['rm_rate'],
                'taxes' => $request['taxes'],
                'no_of_pax' => $request['no_of_pax'],
                'hotel_note' => $request['hotel_note'],
                'client_note' => $request['client_note'],
                'way_of_payment' => $request['way_of_payment'],
                'profile_id' => $request['profile_id'],
                'company_id' => $request['company_id'],
                'room_id' => (!empty($request['room_id'])) ? $request['room_id'] : null,
                'room_type_id' => $request['room_type_id'],
                'rate_code_id' => (!empty($request['rate_code_id'])) ? $request['rate_code_id'] : null,
                'market_segment_id' => $request['market_segment_id'],
                'source_of_business_id' => $request['source_of_business_id'],
                'meal_id' => $request['meal_id'],
                'meal_package_id' => $request['meal_package_id'],
                'created_by' => auth()->id(),
                'created_inhousGuest_at' => null,
                'checked_out' => $request['checked_out'],
                'checkout_by' => 0,
                'checkout_at' => (!empty($request['checkout_at'])) ? $request['rate_code_idcheckout_at'] : null,
                'is_reservation' => 1,
                'res_status' => $request['res_status'],
                'is_group' => 1,
                'group_code' => $request['group_code'],
                'is_dummy' => 0,
                'Is_PM' => 0,
                'res_no' => (!empty($request['res_no'])) ? $request['res_no'] : null,
                'scth_transaction_id' => ($request['res_type']) ? $request->res_type : null,
                'customer_type' => $request['customerType'],
                'purpose_of_visit' => $request['purposeOfVisit'],
            ]
        );
        //$guestInhouse = $this->guestInhouseModel::where('id', $guest->id)->first();


        //start creat window for this room
        $payMasterGuest = $this->guestInhouseModel::where('group_code', $guest->group_code)->where('room_type_id', 1)->get();
        $payMasterWindow = $this->windowModel::where('guest_id', $payMasterGuest->first()->id)->get();
        $windowRequest =
            [
                'window_name' => 'window for' . $guest->id,
                'guest_id' =>  $guest->id,

            ];

        $windowNumber = $payMasterWindow->first()->id;

        $windowData = $this->repositoryWindowInterface->store($windowRequest, null, $windowNumber);
        $windowNumber++;

        $chargeRoutedRequest = [
            'window_id_from' => $windowData->first()->window_number,
            'window_id_to' => $payMasterWindow->first()->id,
            'guest_id_from' => $guest->id,
            'guest_id_to' => $payMasterGuest->first()->id,
        ];
        $chargeRouted = $this->repositoryChargeRoutedInterface->storeDefualtChargeRouted($chargeRoutedRequest);
if($this->settings()->first()->ntmp_active) {
    $ntmp = $this->integrationForNTInterface->NTMPCreateOrUpdate($guest->id, 1);
}

        return $chargeRouted;
        //end create chargeRouted
    }
    public function updateOneInGroupGuest($request, $guest_id = null)
    {
        if (!is_null($guest_id)) {
            $guestInhouse = $this->guestInhouseModel::where('id', $guest_id)->where('locked', 0)->first();
        } else {
            $guest_id = $request['guest_id'];
            $guestInhouse = $this->guestInhouseModel::where('id', $guest_id)->first();
        }

        $statusOfUpdate = $this->guestInhouseModel::where('id', $guest_id)->Update([
            'folio_no'  => (array_key_exists('folio_no', $request)) ? $request['folio_no'] : $guestInhouse->folio_no,
            'in_date'  => (array_key_exists('in_date', $request)) ? $request['in_date'] : $guestInhouse->in_date,
            'out_date'  => (array_key_exists('out_date', $request)) ? $request['out_date'] : $guestInhouse->out_date,
            'original_out_date'  => (array_key_exists('original_out_date', $request)) ? $request['original_out_date'] : $guestInhouse->original_out_date,
            'rm_rate'  => (array_key_exists('rm_rate', $request)  && !empty($request['rm_rate'])) ? $request['rm_rate'] : $guestInhouse->rm_rate,
            'taxes'  => (array_key_exists('taxes', $request)) ? $request['taxes'] : $guestInhouse->taxes,
            'no_of_pax'  => (array_key_exists('no_of_pax', $request) && !empty($request['no_of_pax'])) ? $request['no_of_pax'] : $guestInhouse->no_of_pax,
            'no_of_nights' => (array_key_exists('no_of_nights', $request)) ? $request['no_of_nights'] : $guestInhouse->no_of_nights,
            'hotel_note'  => (array_key_exists('hotel_note', $request)) ? $request['hotel_note'] : $guestInhouse->hotel_note,
            'client_note'  => (array_key_exists('client_note', $request)) ? $request['client_note'] : $guestInhouse->client_note,
            'way_of_payment'  => (array_key_exists('way_of_payment', $request)) ? $request['way_of_payment'] : $guestInhouse->way_of_payment,
            'profile_id'  => (array_key_exists('profile_id', $request)) ? $request['profile_id'] : $guestInhouse->profile_id,
            'company_id'  => (array_key_exists('company_id', $request)) ? $request['company_id'] : $guestInhouse->company_id,
            'room_type_id'  => (array_key_exists('room_type_id', $request)) ? $request['room_type_id'] : $guestInhouse->room_type_id,
            'rate_code_id'  => (array_key_exists('rate_code_id', $request)  && !empty($request['rate_code_id'])) ? $request['rate_code_id'] : $guestInhouse->rate_code_id,
            'market_segment_id'  => (array_key_exists('market_segment_id', $request)) ? $request['market_segment_id'] : $guestInhouse->market_segment_id,
            'source_of_business_id'  => (array_key_exists('source_of_business_id', $request)) ? $request['source_of_business_id'] : $guestInhouse->source_of_business_id,
            'meal_id'  => (array_key_exists('meal_id', $request)) ? $request['meal_id'] : $guestInhouse->meal_id,
            'meal_package_id'  => (array_key_exists('meal_package_id', $request)) ? $request['meal_package_id'] : $guestInhouse->meal_package_id,
            'created_inhousGuest_at'  => (array_key_exists('created_inhousGuest_at', $request)) ? $request['created_inhousGuest_at'] : $guestInhouse->created_inhousGuest_at,
            'checked_out'  => (array_key_exists('checked_out', $request)) ? $request['checked_out'] : $guestInhouse->checked_out,
            'checkout_by'  => (array_key_exists('checkout_by', $request)) ? $request['checkout_by'] : $guestInhouse->checkout_by,
            'checkout_at'  => (array_key_exists('checkout_at', $request)) ? $request['checkout_at'] : $guestInhouse->checkout_at,
            'is_reservation'  => (array_key_exists('is_reservation', $request)) ? $request['is_reservation'] : $guestInhouse->is_reservation,
            'res_status'  => (array_key_exists('res_status', $request)) ? $request['res_status'] : $guestInhouse->res_status,
            'is_group'  => (array_key_exists('is_group', $request)) ? $request['is_group'] : $guestInhouse->is_group,
            'group_code'  => (array_key_exists('group_code', $request)) ? $request['group_code'] : $guestInhouse->group_code,
            'is_dummy'  => (array_key_exists('is_dummy', $request)) ? $request['is_dummy'] : $guestInhouse->is_dummy,
            'room_id'  => (array_key_exists('room_id', $request)) ? $request['room_id'] : $guestInhouse->room_id,
            'Is_PM'  => (array_key_exists('Is_PM', $request)) ? $request['Is_PM'] : $guestInhouse->Is_PM,
            'ref_no'  => (array_key_exists('ref_no', $request)) ? $request['ref_no'] : $guestInhouse->ref_no,

            // 'vat_no'  => (!empty($request['vat_no'])) ? $request['vat_no'] :  $guestInhouse->vat_no,
            'vat_no'  => (array_key_exists('vat_no', $request)) ? $request['vat_no'] : $guestInhouse->vat_no,
            'company_name'  => (array_key_exists('company_name', $request)) ? $request['company_name'] : $guestInhouse->company_name,
            'inv_address'  => (array_key_exists('inv_address', $request)) ? $request['inv_address'] : $guestInhouse->inv_address,
            'vip'  => (array_key_exists('vip', $request)  && !empty($request['vip'])) ? $request['vip'] : $guestInhouse->vip,
            'res_type'  => (array_key_exists('res_type', $request)) ? $request['res_type'] : $guestInhouse->res_type,
            'scth_transaction_id'  => (array_key_exists('scth_transaction_id', $request)) ? $request['scth_transaction_id'] : $guestInhouse->scth_transaction_id,
            'customer_type'  => (array_key_exists('customerType', $request)) ? $request['customerType'] : $guestInhouse->customer_type,
            'purpose_of_visit'  => (array_key_exists('purposeOfVisit', $request)) ? $request['purposeOfVisit'] : $guestInhouse->purpose_of_visit,
            
            'no_of_child'  => (array_key_exists('no_of_child', $request)) ? $request['no_of_child'] : $guestInhouse->no_of_child,
            'no_of_child'  => (array_key_exists('no_of_child', $request)) ? $request['no_of_child'] : $guestInhouse->no_of_child,

        ]);

        if ($guestInhouse->first()->room_id != null) {
            $this->changeRoomStatus($guestInhouse->first()->room_id, 'BL', 'Change By Update Reservation');
        }
        if (array_key_exists('room_id', $request) && $request['room_id'] != null) {
            $this->taskDetailInterface->publicRefreshTask('reservitionNotBlock', 1, 'block_rooms');
        }
if($this->settings()->first()->ntmp_active) {
    $ntmp = $this->integrationForNTInterface->NTMPCreateOrUpdate($guest_id, 2);
}

        return $statusOfUpdate;
    }

    public function updateGroupGuest($requests)
    {
        $count = 0;
        foreach ($requests->groupGuest as $request) {
           
            if (array_key_exists('room_type_id', $request)) {
                if ($request['room_type_id'] == 1 && $request['updated'] == 1) {

                    $this->updateAsPayMasterGuest($request);
                }
            }

            if (!is_null($request['guest_id']) && $request['updated'] == 1 && $request['room_type_id'] != 1) {
                $statusOfUpdate = $this->updateOneInGroupGuest($request);
                if (array_key_exists('res_rate_days', $request))
                {
                    $requestData =new Request;
                    $requestData->merge($request);
                    $guestInhouseWithResRateDays = $this->resRateDaysDelete($requestData->guest_id);
                    $guestInhouseWithResRateDays = $this->resRateDaysStore($requestData, $requestData->guest_id); 

                    $getPrechargeData = $this->preChargeInterface->destroy($requestData->guest_id);
                    $getPrechargeData = $this->preChargeInterface->getPreChargeDAtaForGest($requestData->guest_id);
                    $guestInhouseAddPrecharge = $this->preChargeInterface->preChargeStore($getPrechargeData);
                }
                
            } elseif (is_null($request['guest_id']) && $request['updated'] == 1 && $request['room_type_id'] != 1) {
      
                $this->addGuestInGroup($request);
            }
        }

        return 1;
    }

    public function updateGroupGuestByIds($request)
    {
        foreach ($request->guest_ids as $guest_id) {
            $request['guest_id'] = $guest_id;
            $statusOfUpdate = $this->updateOneInGroupGuest($request->toArray());
        }
        return true;
    }

    public function destroy($id)
    {
        $groupRservsionMaster = $this->guestInhouseModel::where('id', $id)->first();
        if ($groupRservsionMaster) {
            $groupRservsionMaster->delete();
            return 1;
        } else {
            return null;
        }
    }

    public function showGroupRservision($id)
    {
        $groupRservsionMaster = $this->guestInhouseModel::where('id', $id)
        ->whereHas('roomType',function($q){
            $q->where('rm_type_code','GPM');
        })
        //->where('room_type_id', 1)
        ->with('meal', 'rateCode.roomTypes', 'window');

        if ($groupRservsionMaster->first()) {
            $groupRservsionNullRateCode = $this->guestInhouseModel->where('group_code', $groupRservsionMaster->first()->group_code)
                ->with('marketSegment', 'meal', 'createdBy', 'company', 'room', 'rateCode')->WhereNull('guests.rate_code_id')
                ->where('room_type_id', '!=', 1)
                ->get();
            $groupRservsion = $this->guestInhouseModel->where('group_code', $groupRservsionMaster->first()->group_code)
                ->with('marketSegment', 'meal', 'meal_package', 'createdBy', 'company', 'room')
                ->join('ratecodes_roomtypes', function ($join) {
                    $join->on('ratecodes_roomtypes.room_type_id', '=', 'guests.room_type_id')
                        ->on('ratecodes_roomtypes.rate_code_id', '=', 'guests.rate_code_id');
                })

                ->Join('room_types', 'guests.room_type_id', '=', 'room_types.id')

                ->join('rate_codes', function ($join) {
                    $join->on('ratecodes_roomtypes.rate_code_id', '=', 'rate_codes.id');
                })

                ->select(
                    'guests.*',
                    'ratecodes_roomtypes.pax1',
                    'ratecodes_roomtypes.pax2',
                    'ratecodes_roomtypes.pax3',
                    'ratecodes_roomtypes.pax4',
                    'ratecodes_roomtypes.pax5',
                    'ratecodes_roomtypes.pax6',
                    'ratecodes_roomtypes.extra_pax',

                    'room_types.rm_type_code',
                    'room_types.rm_type_name',
                    'room_types.rm_type_name_loc',
                    'room_types.def_pax',
                    'rate_codes.rate_code',
                    'rate_codes.name',
                    'rate_codes.name_loc',
                    'rate_codes.meal_id',
                    'rate_codes.meal_package_id',

                )

                ->where('guests.room_type_id', '!=', 1)
                ->get();
            // dd($groupRservsionNullRateCode);

            // dd($groupRservsion);
            //  $data =  array_merge($groupRservsionNullRateCode->toArray(),$groupRservsion->toArray());
            // $mergedItem =$groupRservsionNullRateCode->add($groupRservsion);
            $data = $groupRservsionMaster->get()->map(function ($q) use ($groupRservsion, $groupRservsionNullRateCode) {
                $q->guestGroup = $groupRservsion;
                $q->guestGroupNullRateCode = $groupRservsionNullRateCode;
                return $q;
            });
            return  $data->all();
        } else {
            return null;
        }
    }
    // public function showGroupRservisioForSelected($id)
    // {
    //     $groupRservsionMaster = $this->guestInhouseModel::where('id', $id)->where('room_type_id',1);
    //     if($groupRservsionMaster->first())
    //     {
    // //         $groupRservsion = $this->guestInhouseModel::where('group_code', $groupRservsionMaster->first()->group_code)
    // //         ->with(['rateCode' => function($q)use($groupRservsionMaster){
    // //         $q->with(['roomTypes' => function($qs){
    // //             $qs->where(DB::raw('ratecodes_roomtypes.room_type_id'),'=',DB::raw('guests.room_type_id'));
    // //         }]);
    // //   //   $q->where(DB::raw('ratecodes_roomtypes.room_type_id'),'=',DB::raw('guests.room_type_id'))
    // //           //  $q->wherePivot('roomType_id', '=', DB::raw('guest.room_type_id'));
    // //         ;
    // //         return $q;
    // //         }])
    // //         ->where('room_type_id','!=',1)
    // //         ->get();
    //         //
    //         $groupRservsion =DB::table('guests')->where('group_code', $groupRservsionMaster->first()->group_code)
    //         ->join('ratecodes_roomtypes','guests.room_type_id','=','ratecodes_roomtypes.room_type_id')
    //         ->join('room_types','guests.room_type_id','=','room_types.id')
    //         //on('guests.room_type_id','=','ratecodes_roomtypes.room_type_id')
    //         ->where('guests.room_type_id','!=',1)
    //         ->get();
    //         //
    //          $data = $groupRservsionMaster->get()->map(function($q)use($groupRservsion){
    //              $q->guestGroup = $groupRservsion;
    //              return $q;
    //          });
    //         // dd( $data->all());
    //          return  $data->all();
    //     }else{
    //         return null;
    //     }



    // }

    //end groupReservision

    public function show($id)
    {

        $room = $this->guestInhouseModel::where('id', $id)->with(
            'profile.country',
            'moreName',
            'room.room_type',
            'room.floors',
            'roomType',
            'rateCode.meal',
            'rateCode.meal_package',
            'rateCode.roomTypes',
            'marketSegment',
            'sourceBusiness',
            'meal',
            'createdBy',
            'resRateDay.rateCode',
        )->first();

        return $room;
    }
    public function update($request, $id)
    {
        // $guestInhouse = $this->guestInhouseModel::where('id', $id)->first();
        $guestInhouse = $this->guestInhouseModel::where('id', $id)->where('locked', 0)->first();
        if ($guestInhouse) {
            // dd(($request->has('customerType')) ? $request['customer_type'] :  $guestInhouse->customer_type);
            $this->guestInhouseModel::where('id', $id)->Update([
                'folio_no'  => ($request->has('folio_no')) ? $request['folio_no'] :  $guestInhouse->folio_no,
                'in_date'  => ($request->has('in_date')) ? $request['in_date'] :  $guestInhouse->in_date,
                'out_date'  => ($request->has('out_date')) ? $request['out_date'] :  $guestInhouse->out_date,
                'original_out_date'  => ($request->has('original_out_date')) ? $request['original_out_date'] :  $guestInhouse->original_out_date,
                'rm_rate'  => ($request->has('rm_rate')) ? $request['rm_rate'] :  $guestInhouse->rm_rate,
                'taxes'  => ($request->has('taxes')) ? $request['taxes'] :  $guestInhouse->taxes,
                'no_of_pax'  => ($request->has('no_of_pax')) ? $request['no_of_pax'] :  $guestInhouse->no_of_pax,
                'no_of_nights' => ($request->has('no_of_nights')) ? $request['no_of_nights'] :  $guestInhouse->no_of_nights,
                'hotel_note'  => ($request->has('hotel_note')) ? $request['hotel_note'] :  $guestInhouse->hotel_note,
                'client_note'  => ($request->has('client_note')) ? $request['client_note'] :  $guestInhouse->client_note,
                'way_of_payment'  => ($request->has('way_of_payment')) ? $request['way_of_payment'] :  $guestInhouse->way_of_payment,
                'profile_id'  => ($request->has('profile_id')) ? $request['profile_id'] :  $guestInhouse->profile_id,
                'company_id'  => ($request->has('company_id')) ? $request['company_id'] :  $guestInhouse->company_id,
                'room_type_id'  => ($request->has('room_type_id')) ? $request['room_type_id'] :  $guestInhouse->room_type_id,
                'rate_code_id'  => ($request->has('rate_code_id')) ? $request['rate_code_id'] :  $guestInhouse->rate_code_id,
                'market_segment_id'  => ($request->has('market_segment_id')) ? $request['market_segment_id'] :  $guestInhouse->market_segment_id,
                'source_of_business_id'  => ($request->has('source_of_business_id')) ? $request['source_of_business_id'] :  $guestInhouse->source_of_business_id,
                'meal_id'  => ($request->has('meal_id')) ? $request['meal_id'] :  $guestInhouse->meal_id,
                'meal_package_id'  => ($request->has('meal_package_id')) ? $request['meal_package_id'] :  $guestInhouse->meal_package_id,
                'created_inhousGuest_at'  => ($request->has('created_inhousGuest_at')) ? $request['created_inhousGuest_at'] :  $guestInhouse->created_inhousGuest_at,
                'checked_out'  => ($request->has('checked_out')) ? $request['checked_out'] :  $guestInhouse->checked_out,
                'checkout_by'  => ($request->has('checkout_by')) ? $request['checkout_by'] :  $guestInhouse->checkout_by,
                'checkout_at'  => ($request->has('checkout_at')) ? $request['checkout_at'] :  $guestInhouse->checkout_at,
                'is_reservation'  => ($request->has('is_reservation')) ? $request['is_reservation'] :  $guestInhouse->is_reservation,
                'res_status'  => ($request->has('res_status')) ? $request['res_status'] :  $guestInhouse->res_status,
                'is_group'  => ($request->has('is_group')) ? $request['is_group'] :  $guestInhouse->is_group,
                'group_code'  => ($request->has('group_code')) ? $request['group_code'] :  $guestInhouse->group_code,
                'is_dummy'  => ($request->has('is_dummy')) ? $request['is_dummy'] :  $guestInhouse->is_dummy,
                'room_id'  => ($request->has('room_id')) ? $request['room_id'] :  $guestInhouse->room_id,
                'Is_PM'  => ($request->has('Is_PM')) ? $request['Is_PM'] :  $guestInhouse->Is_PM,
                'ref_no'  => ($request->has('ref_no')) ? $request['ref_no'] :  $guestInhouse->ref_no,
                'res_no'  => ($request->has('res_no')) ? $request['res_no'] :  $guestInhouse->res_no,

                // 'vat_no'  => (!empty($request['vat_no'])) ? $request['vat_no'] :  $guestInhouse->vat_no,
                'vat_no'  => ($request->has('vat_no')) ? $request['vat_no'] :  $guestInhouse->vat_no,
                'company_name'  => ($request->has('company_name')) ? $request['company_name'] :  $guestInhouse->company_name,
                'inv_address'  => ($request->has('vat_no')) ? $request['inv_address'] :  $guestInhouse->inv_address,
                'vip'  => ($request->has('vip')) ? $request['vip'] :  $guestInhouse->vip,
                'res_type'  => ($request->has('res_type')) ? $request['res_type'] :  $guestInhouse->res_type,
                'scth_transaction_id'  => ($request->has('scth_transaction_id')) ? $request['scth_transaction_id'] :  $guestInhouse->scth_transaction_id,
                'customer_type'  => ($request->has('customerType')) ? $request['customerType'] :  $guestInhouse->customer_type,
                'purpose_of_visit'  => ($request->has('purposeOfVisit')) ? $request['purposeOfVisit'] :  $guestInhouse->purpose_of_visit,
           
                'no_of_child' => ($request->has('no_of_child')) ? $request->no_of_child : $guestInhouse->no_of_child,
                'no_of_inf' =>($request->has('no_of_inf')) ? $request->no_of_inf : $guestInhouse->no_of_inf,

                'shomoos_id' =>(!empty($request->shomoos_id)) ? $request->shomoos_id : $guestInhouse->shomoos_id,
                'is_sent_shomoos' =>(!empty($request->is_sent_shomoos)) ? $request->is_sent_shomoos : $guestInhouse->is_sent_shomoos,
            ]);
            if (($request->has('res_status')) && $request['res_status'] == "CNF") {
                $this->taskDetailInterface->publicRefreshTask('reservitionNotConferm', 1, 'confirm_reservation');
            }
            if (($request->has('room_id')) && $request['room_id'] != null) {
                $this->taskDetailInterface->publicRefreshTask('reservitionNotBlock', 1, 'block_rooms');
            }


            if ($guestInhouse->room_id !=  ($request->has('room_id')) ? $request['room_id'] :  $guestInhouse->room_id) {
                $this->changeRoomStatus($guestInhouse->room_id, 'VA', 'Change By Update Reservation');
            }


            $guestInhouse = $this->guestInhouseModel::where('id', $id)->with(
                'attachment',
                'moreName',
                'profile',
                //   'company',
                'room',
                'roomType',
                'rateCode',
                'marketSegment',
                'sourceBusiness',
                'meal',
                'createdBy'
            )->get();

            if ($guestInhouse->first()->room_id != null) {
                $this->changeRoomStatus($guestInhouse->first()->room_id, 'BL', 'Change By Update Reservation');
            }
if($this->settings()->first()->ntmp_active) {
    $ntmp = $this->integrationForNTInterface->NTMPCreateOrUpdate($guestInhouse->first()->id, 2, 1);
}

            return  $guestInhouse;
        } else {
            return null;
        }
    }


    public function isGroupUpdate($request, $id)
    {
        $isGroub = $this->guestInhouseModel::where('id', $id)->first();
        if ($isGroub) {
            $this->guestInhouseModel::where('id', $id)->Update([

                'is_group'  => $request['is_group'],

            ]);
            $isGroub = $this->guestInhouseModel::where('id', $id)->with(
                'profile',
                //   'company',
                'room',
                'roomType',
                'rateCode',
                'marketSegment',
                'sourceBusiness',
                'meal',
                'createdBy',
            )->get();
if($this->settings()->first()->ntmp_active) {
    $ntmp = $this->integrationForNTInterface->NTMPCreateOrUpdate($id, 2, 1);
}

            return  $isGroub;
        } else {
            return null;
        }
    }
    public function isDummy($request, $id)
    {
        $isDummy = $this->guestInhouseModel::where('id', $id)->first();
        if ($isDummy) {
            $this->guestInhouseModel::where('id', $id)->Update([

                'is_dummy'  => $request['is_dummy'],
            ]);
            $isDummy = $this->guestInhouseModel::where('id', $id)->with(
                'profile',
                //   'company',
                'room',
                'roomType',
                'rateCode',
                'marketSegment',
                'sourceBusiness',
                'meal',
                'createdBy',
            )->get();
            return  $isDummy;
        } else {
            return null;
        }
    }
    public function IsPM($request, $id)
    {
        $IsPM = $this->guestInhouseModel::where('id', $id)->first();
        if ($IsPM) {
            $this->guestInhouseModel::where('id', $id)->Update([

                'Is_PM'  => $request['Is_PM'],

            ]);
            $IsPM = $this->guestInhouseModel::where('id', $id)->with(
                'profile',
                //   'company',
                'room',
                'roomType',
                'rateCode',
                'marketSegment',
                'sourceBusiness',
                'meal',
                'createdBy',
            )->get();
            return  $IsPM;
        } else {
            return null;
        }
    }
    public function isCancel($request)
    {


        $isCancel = $this->guestInhouseModel::where('id', $request->guest_id)->first();
        if ($isCancel) {
            $this->guestInhouseModel::where('id', $request->guest_id)->Update([

                'is_cancel'  => true,
                'res_status' => 'CNC'
            ]);
            // $this->callAndUpdateDueOutTaskDetails();

            $isCancel = $this->guestInhouseModel::where('id', $request->guest_id)->with(
                'profile',
                //   'company',
                'room',
                'roomType',
                'rateCode',
                'marketSegment',
                'sourceBusiness',
                'meal',
                'createdBy',
            )->get();
if($this->settings()->first()->ntmp_active) {
    $ntmp = $this->integrationForNTInterface->NTMPCancelBooking($request->guest_id, $request->cancelReason);
}
            return  $isCancel;
        } else {
            return null;
        }
    }
    public function callAndUpdateDueOutTaskDetails()
    {
        $hotelDate = $this->settings()->first()->hotel_date;
        $dueOut = $this->taskDetailInterface->dueOut($hotelDate);
        if ($dueOut == 0) {
            $expectedFinish = $this->taskDetailModel
                ->whereHas('tasks', function ($q) {
                    return $q->where('name', 'due_out');
                })
                ->where('actual_finish', null)->whereDate('expected_finish', $this->settings()->first()->hotel_date)
                ->select('id', 'expected_finish')->latest()->first();
            if ($expectedFinish) {
                $request = new Request;
                $request->merge(['name' => 'due_out', 'actual_finish' => now()->format('Y-m-d H:i:s'), 'late_time' => $this->taskDetailInterface->lateTime($expectedFinish->expected_finish, now())]);
                $dueOut = $this->taskDetailInterface->update($request, $expectedFinish->id);
                return true;
            }
        }
    }
    public function groubeCancel($request)
    {


        $isCancel = $this->guestInhouseModel::where('group_code', $request->groubeCode)->get();
        if ($isCancel) {
            $this->guestInhouseModel::where('group_code', $request->groubeCode)->Update([

                'is_cancel'  => true,
                'res_status' => 'CNC'
            ]);
            $groubeCanceled = $this->guestInhouseModel::where('group_code', $request->groubeCode)->with(
                'profile',
                //   'company',
                'room',
                'roomType',
                'rateCode',
                'marketSegment',
                'sourceBusiness',
                'meal',
                'createdBy',
            )->get();
if($this->settings()->first()->ntmp_active) {
    foreach ($groubeCanceled as $guestCancel) {
        $ntmp = $this->integrationForNTInterface->NTMPCancelBooking($guestCancel->id, $request->cancelReason);
    }
}

            return  $groubeCanceled;
        } else {
            return null;
        }
    }
    public function isCheckedIn($id)
    {
        $isCheckedIn = $this->guestInhouseModel::where('id', $id)->with('room')->first();
        $requestToAvailability = new Request;
        $requestToAvailability->merge($isCheckedIn->toArray());
        $roomAvailablity = $this->availability($requestToAvailability);
        //dd($isCheckedIn);
        //dd($roomAvailablity['roomAvailableCount']);
        //dd($roomAvailablity);
        if ($roomAvailablity['roomAvailableCount'] == 'NotAvailable') {
            return 'NotAvailable';
        }
        if ($isCheckedIn) {
            $this->guestInhouseModel::where('id', $id)->Update([

                'is_reservation' => false,
                'res_status' => 'CHK',
                'is_checked_in' => true,
                'created_inhousGuest_at' => now()
            ]);
            $this->changeRoomStatus($isCheckedIn->room_id, 'OC', 'By Check In');

          //****************start shomoos */ 
          $api = 'https://masashomoos.masacloud.net/api/InsertGuest';
          $guestHandeledData = $this->ShomoosIntegrationInterface->handelShomoosDataForStore($isCheckedIn,$api);
          $shomoosResponse = $this->shomoosInterface->store($guestHandeledData);

          //****************end shomoos */           
if($this->settings()->first()->ntmp_active) {

    $ntmp = $this->integrationForNTInterface->NTMPCreateOrUpdate($id, 2, 2);
}

            return  'done';
        } else {
            return null;
        }
    }

    public function payMasterCheckIn($id)
    {
        $guest = $this->guestInhouseModel::where('id', $id)->select('id','group_code')->first();
        $payMaster = $this->guestInhouseModel::where('group_code',  $guest->group_code)->where('is_checked_in',0)->whereHas('roomType',function($q){
            $q->where('rm_type_name','payMaster');
        })->select('id')->first();
        if($payMaster)
        {
            $this->guestInhouseModel::where('id', $payMaster->id)->Update([

                'is_reservation' => false,
                'res_status' => 'CHK',
                'is_checked_in' => true,
                'created_inhousGuest_at' => now()
            ]);
            return true;
        }else{
            return 'Is Checked In';
        }
    }
    public function isOnline($request, $id)
    {
        $isOnline = $this->guestInhouseModel::where('id', $id)->first();
        if ($isOnline) {
            $this->guestInhouseModel::where('id', $id)->Update([

                'is_online'  => $request['is_online'],

            ]);
            $isOnline = $this->guestInhouseModel::where('id', $id)->with(
                'profile',
                //   'company',
                'room',
                'roomType',
                'rateCode',
                'marketSegment',
                'sourceBusiness',
                'meal',
                'createdBy',
            )->get();
            return  $isOnline;
        } else {
            return null;
        }
    }
    public function reInstate($id)
    {
        $reservision = $this->guestInhouseModel::where('id', $id)->first();
        if ($reservision) {
            $this->guestInhouseModel::where('id', $id)->Update([

                'is_cancel' => false,
                'res_status' => 'CNF'
            ]);
            $reservision = $this->guestInhouseModel::where('id', $id)->with(
                'profile',
                //   'company',
                'room',
                'roomType',
                'rateCode',
                'marketSegment',
                'sourceBusiness',
                'meal',
                'createdBy',
            )->get();
            return  $reservision;
        } else {
            return null;
        }
    }
    public function reservisionUpdate($request, $id)
    {
        $reservision = $this->guestInhouseModel::where('id', $id)->first();
        if ($reservision) {
            $this->guestInhouseModel::where('id', $id)->Update([

                'is_reservation'  => $request['is_reservation'],

            ]);
            $reservision = $this->guestInhouseModel::where('id', $id)->with(
                'profile',
                //   'company',
                'room',
                'roomType',
                'rateCode',
                'marketSegment',
                'sourceBusiness',
                'meal',
                'createdBy',
            )->get();
            return  $reservision;
        } else {
            return null;
        }
    }

    public function availability($request)
    {
        $carbonStartDate = Carbon::parse($request->in_date);
        $carbonEndDate = Carbon::parse($request->out_date);
        $roomsTybe = $this->roomTypeModel::where('rm_type_name', '!=', 'payMaster')
        ->where('def_pax','>',0)
        ->get(['id', 'no_of_rooms', 'rm_type_name', 'rm_type_name_loc', 'def_pax', 'monthly_rate', 'yearly_rate']);
        $roomCount = [];
        $notAvailableRoom = [];
        //**start check by room id */
        if ($request->room_id) {
            $rooms = $this->roomModel::where('id', $request->room_id)->with('room_type')->get();
            $roomTypeName = $rooms->first()->room_type->rm_type_name;
            //*******************check in out of order */
            $countRoomsOord =  $this->OordServices::where('room_id', $request->room_id)->whereBetween('start_date', [$carbonStartDate, $carbonEndDate])->where('is_return', 0)->orWhereBetween('end_date', [$carbonStartDate, $carbonEndDate])->where('room_id', $request->room_id)->where('is_return', 0)->count();
            if ($countRoomsOord) {
                return ['romeTypeName' => $roomTypeName, 'roomAvailableCount' => 'NotAvailable'];
            }
            //******************check in guest reservission and inhous */
            else {
                //  dd($request->room_id);
                $notAvelableGuest =  $this->guestInhouseModel::where('room_id', $request->room_id)
                    ->where(function ($q) use ($request) {
                        if ($request->id) {
                            $q->where('id', '!=', ($request->has('id')) ? $request->id : $request->guest_id);
                            // return $q;
                        }
                    })->where(function ($q) use ($request, $carbonStartDate, $carbonEndDate) {
                        // dd(in_array('duOut',$request->toArray()));
                        if (collect($request)->has('duOut') && $request->duOut == true || in_array('duOut', $request->toArray()) && $request['duOut'] == true) {

                            return  $q->whereDate('in_date', '<=', $carbonEndDate)->whereDate('out_date', '>', $carbonStartDate);
                        } else {
                            return   $q->whereDate('in_date', '<=', $carbonEndDate)->whereDate('out_date', '>=', $carbonStartDate);
                        }
                    })->Where('is_reservation', 1)->Where(function ($query) {
                        $query->Where('res_status', 'CNF');
                        $query->orWhere('res_status', 'NCF');
                    })->Where('is_cancel', 0)->Where('res_status', '!=', 'CNC')->Where('checked_out', 0)->
                    // Where(function($q) use($request)
                    // {
                    //     if($request->has('guest_id')){
                    //        $q->Where('guest_id',$request->guest_id);
                    //     }
                    // })->

                    //orWhereBetween('out_date',[$carbonStartDate,$carbonEndDate])->
                    orWhere(function ($q) use ($request, $carbonStartDate, $carbonEndDate) {

                        // dd(in_array('duOut',$request->toArray()));
                        if (collect($request)->has('duOut') && $request->duOut == true || in_array('duOut', $request->toArray()) && $request['duOut'] == true) {

                            return  $q->whereDate('in_date', '<=', $carbonEndDate)->whereDate('out_date', '>', $carbonStartDate);
                        } else {
                            return   $q->whereDate('in_date', '<=', $carbonEndDate)->whereDate('out_date', '>=', $carbonStartDate);
                        }
                    })
                    ->where(function ($q) use ($request) {
                        if ($request->id) {
                            $q->where('id', '!=', ($request->has('id')) ? $request->id : $request->guest_id);
                            // return $q;
                        }
                    })->
                    // whereTime('in_date','<',$carbonEndDate->format('H:i:s'))->
                    where('room_id', $request->room_id)->Where('is_reservation', 1)->Where(function ($query) {
                        $query->Where('res_status', 'CNF');
                        $query->orWhere('res_status', 'NCF');
                    })->
                    // Where(function($q) use($request)
                    // {
                    //     if($request->id != null){
                    //        $q->Where('id',$request->id);
                    //     }
                    // })->
                    Where('is_cancel', 0)->Where('res_status', '!=', 'CNC')->Where('checked_out', 0)
                    /**to check if guest is checked in**/
                    ->orWhere(function ($query) use ($carbonStartDate, $carbonEndDate, $request) {

                        if ($request->id) {
                            $query->where('id', '!=', ($request->has('id')) ? $request->id : $request->guest_id);
                        }


                        if (collect($request)->has('duOut') && $request->duOut == true || in_array('duOut', $request->toArray()) && $request['duOut'] == true) {
                            return  $query->whereDate('in_date', '<=', $carbonEndDate)->whereDate('out_date', '>', $carbonStartDate);
                        } else {
                            $query->whereDate('in_date', '<=', $carbonEndDate)->whereDate('out_date', '>=', $carbonStartDate);
                        }


                        // $query-> whereDate('in_date','<=',$carbonEndDate);
                        $query->where('room_id', $request->room_id);
                        $query->where('is_checked_in', 1);
                        $query->where('is_reservation', 0);
                        $query->where('checked_out', 0);
                    })
                    ->with('roomType')->get();
                if (!empty($notAvelableGuest->first())) {

                    return ['romeTypeName' => $roomTypeName, 'roomAvailableCount' => 'NotAvailable'];
                } else {
                    $rooms = $this->roomModel::where('id', $request->room_id)->with('floors', 'room_type', 'ViewGardens', 'roomStatus')->get()
                        ->map(
                            function ($q) {
                                $q->selected = false;
                                return $q;
                            }
                        );
                    $roomTypeName = $rooms->first()->room_type->rm_type_name;
                    return ['romeTypeName' => $roomTypeName, 'roomAvailableCount' => 'available', 'roomData' => $rooms];
                }
            }
        }
        //** start check by room type id */
        elseif ($request->room_type_id) {
            $roomType = $this->roomTypeModel::where('id', $request->room_type_id)->withcount('rooms')->first();
            $rooms = $this->roomTypeModel::where('id', $request->room_type_id)->with('rooms')->first();
            $roomTypeName = $roomType->rm_type_name;

            //*******************check in out of order */

            $countRoomsOord =  $this->OordServices::whereHas('room', function ($q) use ($request) {
                $q->where('room_type_id', $request->room_type_id);
            })->whereBetween('start_date', [$carbonStartDate, $carbonEndDate])->where('is_return', 0)->orWhereBetween('end_date', [$carbonStartDate, $carbonEndDate])->whereHas('room', function ($q) use ($request) {
                $q->where('room_type_id', $request->room_type_id);
            })->where('is_return', 0)->get();
            foreach ($countRoomsOord as $romNotAvailable) {
                if ($romNotAvailable->room_id != null) {
                    array_push($notAvailableRoom, $romNotAvailable->room_id);
                }
            }
            //******************check in guest reservission and inhous */
            $notAvelableGuest =  $this->guestInhouseModel::where('room_type_id', $request->room_type_id)->where(function ($q) use ($request, $carbonStartDate, $carbonEndDate) {
                // dd(in_array('duOut',$request->toArray()));
                if (collect($request)->has('duOut') && $request->duOut == true || in_array('duOut', $request->toArray()) && $request['duOut'] == true) {

                    return  $q->whereDate('in_date', '<=', $carbonEndDate)->whereDate('out_date', '>', $carbonStartDate);
                } else {
                    return   $q->whereDate('in_date', '<=', $carbonEndDate)->whereDate('out_date', '>=', $carbonStartDate);
                }
            })->whereDate('in_date', '<=', $carbonEndDate)->whereDate('out_date', '>=', $carbonStartDate)->Where('is_reservation', 1)->Where(function ($query) {
                $query->Where('res_status', 'CNF');
                $query->orWhere('res_status', 'NCF');
            })->Where(function ($q) use ($request) {
                if ($request->id != null) {
                    $q->Where('id', $request->id);
                }
            })->Where('is_cancel', 0)->Where('res_status', '!=', 'CNC')->Where('checked_out', 0)->orWhere(function ($q) use ($request, $carbonStartDate, $carbonEndDate) {
                if (collect($request)->has('duOut') && $request->duOut == true) {
                    $q->whereDate('in_date', '<=', $carbonEndDate)->whereDate('out_date', '>', $carbonStartDate);
                } else {
                    $q->whereDate('in_date', '<=', $carbonEndDate)->whereDate('out_date', '>=', $carbonStartDate);
                }
            })->where('room_type_id', $request->room_type_id)->Where('is_reservation', 1)->Where(function ($query) {
                $query->Where('res_status', 'CNF');
                $query->orWhere('res_status', 'NCF');
            })->Where(function ($q) use ($request) {
                if ($request->id != null) {
                    $q->Where('id', $request->id);
                }
            })->Where('is_cancel', 0)->Where('res_status', '!=', 'CNC')->Where('checked_out', 0)->
                /** start check in guest is checked in */
                orWhere(function ($query) use ($carbonStartDate, $carbonEndDate, $request) {
                    if (collect($request)->has('duOut') && $request->duOut == true) {
                        $query->whereDate('in_date', '<=', $carbonEndDate)->whereDate('out_date', '>', $carbonStartDate);
                    } else {
                        $query->whereDate('in_date', '<=', $carbonEndDate)->whereDate('out_date', '>=', $carbonStartDate);
                    }
                    $query->where('room_type_id', $request->room_type_id);
                    $query->where('is_checked_in', 1);
                    $query->where('is_reservation', 0);
                    $query->where('checked_out', 0);
                    $query->Where(function ($q) use ($request) {
                        if ($request->id != null) {
                            $q->Where('id', $request->id);
                        }
                    });
                })->with('roomType')->get();
            // dd($notAvelableGuest);
            foreach ($notAvelableGuest as $romNotAvailable) {
                if ($romNotAvailable->room_id != null) {
                    if (!in_array($romNotAvailable->room_id, $notAvailableRoom)) {
                        if ($romNotAvailable->room_id != null) {
                            array_push($notAvailableRoom, $romNotAvailable->room_id);
                        }
                    }
                }
            }

            $availableRooms = $this->roomModel::where('room_type_id', $request->room_type_id)->with('floors', 'room_type', 'ViewGardens', 'roomStatus')
                ->whereNotIn('id', $notAvailableRoom)->get()
                ->map(
                    function ($q) {
                        $q->selected = false;
                        return $q;
                    }
                );

            if (!empty($notAvelableGuest->first())) {

                if ($countRoomsOord->first()) {
                    $countAvailable = $roomType->rooms_count - $notAvelableGuest->count() - $countRoomsOord->count();
                } else {
                    // dd($notAvelableGuest->count());
                    $countAvailable = $roomType->rooms_count - $notAvelableGuest->count();
                }

                if ($roomType->rooms_count <= 0 || $countAvailable <= 0) {

                    return ['romeTypeName' => $roomTypeName, 'roomAvailableCount' => 'NotAvailable'];
                } else {
                    if (!empty($countRoomsOord->first())) {
                        // array_push( $roomCount,['room_type_id' =>"$roomType->id" ,'nameOfType' => "$roomType->rm_type_name",'nameOfTypeLoc' =>"$roomType->rm_type_name_loc" ,'def_pax' =>"$roomType->def_pax" , 'countOfType' => (  $totCountRoomType - $notAvelableGuest->count() - $countRoomsOord->count()), 'roomAvailable' => $availableRooms])    ;

                        return [
                            'id' => "$roomType->id", 'rm_type_name' => $roomTypeName,
                            'rm_type_name_loc' => "$roomType->rm_type_name_loc", 'def_pax' => "$roomType->def_pax",
                            "yearly_rate" => $roomType->yearly_rate, 'monthly_rate' => $roomType->monthly_rate, 'roomAvailableCount' => $countAvailable - $countRoomsOord->count(), 'rooms' => $availableRooms
                        ];
                    } else {

                        return [
                            'id' => "$roomType->id", 'rm_type_name' => $roomTypeName, 'rm_type_name_loc' => "$roomType->rm_type_name_loc",
                            'def_pax' => "$roomType->def_pax", "yearly_rate" => $roomType->yearly_rate, 'monthly_rate' => $roomType->monthly_rate, 'roomAvailableCount' => $countAvailable, 'rooms' => $availableRooms
                        ];
                    }
                }
            } else {
                if (!empty($countRoomsOord->first())) {
                    return [
                        'id' => "$roomType->id", 'rm_type_name' => $roomTypeName, 'rm_type_name_loc' => "$roomType->rm_type_name_loc", 'def_pax' => "$roomType->def_pax",
                        "yearly_rate" => $roomType->yearly_rate, 'monthly_rate' => $roomType->monthly_rate, 'roomAvailableCount' => $roomType->room_type_count - $countRoomsOord->count(), 'rooms' => $availableRooms
                    ];
                } else {
                    return [
                        'id' => "$roomType->id", 'rm_type_name' => $roomTypeName, 'rm_type_name_loc' => "$roomType->rm_type_name_loc",
                        'def_pax' => "$roomType->def_pax",
                        "yearly_rate" => $roomType->yearly_rate, 'monthly_rate' => $roomType->monthly_rate, 'roomAvailableCount' => $roomType->rooms_count, 'rooms' => $availableRooms
                    ];
                }
            }
        }
        //** start check for all rooms type */
        else {
            foreach ($roomsTybe as $roomType) {

                //*******************check in out of order */

                $countRoomsOord =  $this->OordServices::whereHas('room', function ($q) use ($roomType) {
                    $q->select('id','room_type_id');
                    $q->whereIn('room_type_id', array($roomType->id));
                })
                ->whereBetween('start_date', [$carbonStartDate, $carbonEndDate])
                ->where('is_return', 0)
                ->orWhereBetween('end_date', [$carbonStartDate, $carbonEndDate])
                ->whereHas('room', function ($q) use ($roomType) {
                    $q->select('id','room_type_id');
                    $q->whereIn('room_type_id', array($roomType->id));
                })
                ->where('is_return', 0)->select('id','room_id')->get();
             
                foreach ($countRoomsOord as $romNotAvailable) {
                    if ($romNotAvailable->room_id != null) {
                        array_push($notAvailableRoom, $romNotAvailable->room_id);
                    }
                }
                //******************check in guest reservission and inhous */
                $totCountRoomType  = $this->roomModel::where('room_type_id', $roomType->id)->count();
                //dd($roomType->id);
                $notAvelableGuest =  $this->guestInhouseModel::where('room_type_id', $roomType->id)
                ->where(function ($q) use ($request, $carbonStartDate, $carbonEndDate) {
                    // dd(in_array('duOut',$request->toArray()));
                    if (collect($request)->has('duOut') && $request->duOut == true || in_array('duOut', $request->toArray()) && $request['duOut'] == true) {

                        $q->whereDate('in_date', '<=', $carbonEndDate)->whereDate('out_date', '>', $carbonStartDate);
                    } else {
                        $q->whereDate('in_date', '<=', $carbonEndDate)->whereDate('out_date', '>=', $carbonStartDate);
                    }
                })->Where('is_reservation', 1)->Where(function ($query) {
                    $query->Where('res_status', 'CNF');
                    $query->orWhere('res_status', 'NCF');
                })->Where('is_cancel', 0)->Where('res_status', '!=', 'CNC')->Where('checked_out', 0)->orWhere(function ($q) use ($request, $carbonStartDate, $carbonEndDate) {
                    if (collect($request)->has('duOut') && $request->duOut == true || in_array('duOut', $request->toArray()) && $request['duOut'] == true) {

                        $q->whereDate('in_date', '<=', $carbonEndDate)->whereDate('out_date', '>', $carbonStartDate);
                    } else {
                        $q->whereDate('in_date', '<=', $carbonEndDate)->whereDate('out_date', '>=', $carbonStartDate);
                    }
                })->where('room_type_id', $roomType->id)->Where('is_reservation', 1)->Where(function ($query) {
                    $query->Where('res_status', 'CNF');
                    $query->orWhere('res_status', 'NCF');
                })->Where('is_cancel', 0)->Where('res_status', '!=', 'CNC')->Where('checked_out', 0)->

                    /* start check in guest checked in */
                    orWhere(function ($query) use ($carbonStartDate, $carbonEndDate, $request, $roomType) {
                        if (collect($request)->has('duOut') && $request->duOut == true || in_array('duOut', $request->toArray()) && $request['duOut'] == true) {

                            $query->whereDate('in_date', '<=', $carbonEndDate)->whereDate('out_date', '>', $carbonStartDate);
                        } else {
                            $query->whereDate('in_date', '<=', $carbonEndDate)->whereDate('out_date', '>=', $carbonStartDate);
                        }
                        $query->where('room_type_id', $roomType->id);
                        $query->where('is_checked_in', 1);
                        $query->where('is_reservation', 0);
                        $query->where('checked_out', 0);
                        $query->Where(function ($q) use ($request) {
                            if ($request->id != null) {
                                $q->Where('id', $request->id);
                            }
                        });
                    })->with('roomType')->select('id','room_id','room_type_id','in_date','out_date')->get();
          
                //************************************************************************************************************************* */

                foreach ($notAvelableGuest as $romNotAvailable) {
                    if (!in_array($romNotAvailable->room_id, $notAvailableRoom)) {
                        if ($romNotAvailable->room_id != null) {
                            array_push($notAvailableRoom, $romNotAvailable->room_id);
                        }
                    }
                }

                if (empty($notAvailableRoom[0])) {
                    $availableRooms = $this->roomModel::where('room_type_id', $roomType->id)->with('floors', 'room_type', 'ViewGardens', 'roomStatus')
                        ->get()
                        ->map(
                            function ($q) {
                                $q->selected = false;
                                return $q;
                            }
                        );
                } else {
                    $availableRooms = $this->roomModel::where('room_type_id', $roomType->id)->with('floors', 'room_type', 'ViewGardens', 'roomStatus')
                        ->whereNotIn('id', $notAvailableRoom)->get()
                        ->map(
                            function ($q) {
                                $q->selected = false;
                                return $q;
                            }
                        );
                }

                if ($countRoomsOord) {
                    array_push($roomCount, [
                        'id' => "$roomType->id", 'rm_type_name' => "$roomType->rm_type_name", 'rm_type_name_loc' => "$roomType->rm_type_name_loc",
                        'def_pax' => "$roomType->def_pax",
                        "yearly_rate" => $roomType->yearly_rate, 'monthly_rate' => $roomType->monthly_rate, 'countOfType' => ($totCountRoomType - $notAvelableGuest->count() - $countRoomsOord->count()), 'rooms' => $availableRooms
                    ]);
                } else {
                    array_push($roomCount, ['id' => "$roomType->id", 'rm_type_name' => "$roomType->rm_type_name", 'rm_type_name_loc' => "$roomType->rm_type_name_loc", 'def_pax' => "$roomType->def_pax", 'countOfType' => ($totCountRoomType - $notAvelableGuest->count()), 'roomAvailable' => $availableRooms]);
                }
            }
        }

        return $roomCount;
    }

    public function AvailabilityScreenData($request)
    {
        $carbonStartDate = Carbon::parse($request->in_date);
        $carbonEndDate = Carbon::parse($request->out_date);
        $allGuests = $this->guestInhouseModel;
        $result = [];
        $return = [];
        $allRoomsCount = $this->roomModel::whereHas('room_type', function ($q) {
            $q->where('rm_type_rentable', 1)->where('def_pax','>',0);

        })->count();
        $result['total'] = $allRoomsCount;
        for ($date = $request->in_date; $date <= $request->out_date; $date = date('Y-m-d', strtotime($date . ' +1 day'))) {
            $result['HDate'] = Carbon::parse($date)->format('Y-m-d l');
            $result['HigryHDate'] = $this->gregorianToHijri($date);
           // dd($result['HigryHDate']);
            $result['arrival'] = $allGuests->whereDate('in_date', $date)->where('is_checked_in',0)->count();
            $result['out'] = $allGuests->whereDate('out_date', $date)->where('checked_out',0)->count();
            
            $result['oc'] = $allGuests->where('in_date', '<=', $date)->where('is_cancel', 0)->where('checked_out', 0)
                ->Where('out_date', '>=', $date)
                ->count();
            $result['%'] = ($result['total'] != 0) ? $result['oc'] / $result['total'] * 100 : 0;
            $result['va'] = $result['total'] - $result['oc'];
            $newRequest = new Request;
            $dateForavailable = ['in_date' => $date, 'out_date' => $date];
            $newRequest->merge($dateForavailable);
            //$newRequest->merge($request->toArray());
            $newRequest['room_type_id'] = $request->room_type_id;
            $newRequest['duOut'] = $request->duOut;
            $availability = $this->availability($newRequest);
            if ($newRequest->room_type_id) {
                $result['availablityRoomTypeCount'] = [
                    'IdOfType' => $availability['id'],
                    'nameOfType' => $availability['rm_type_name'],
                    'nameLocOfType' => $availability['rm_type_name_loc'],
                    'countOfType' => $availability['roomAvailableCount'],
                ];
            } else {
                $result['availablityRoomTypeCount'] = collect($availability)->map(function ($item) {
                    return [
                        'IdOfType' => $item['id'],
                        'nameOfType' => $item['rm_type_name'],
                        'nameLocOfType' => $item['rm_type_name_loc'],
                        'countOfType' => $item['countOfType'],
                    ];
                })->all();
            }

            array_push($return, $result);
        }
        return $return;
    }

    public function checkOut($request)
    {
        //  $this->taskDetailInterface->publicRefreshTask('dueOut',1,'due_out');
        $result = [];
        $tenant_id = $this->tenantModel->current();
        if ($tenant_id) {
            $settings = cache('settings_' . $tenant_id->id);
        } else {
            $settings = $this->settingModel->first();
        }
        $timeOfNow = now()->format('H:m:s');

        $guestcheck = $this->guestInhouseModel->where('id', $request->guest_id)->where('is_checked_in', 1)->where('checked_out', 0)->first();
        if (!$guestcheck) {
            return 'Guest Not Check In';
        }
        foreach ($request->window_id as $window) {
            if ($request->has('balance')) {
                $palanceRequest = ['guest_id' => $request->guest_id, 'window_id' => $window];
                $palance = $request['balance'];
            } else {
                $palanceRequest = ['guest_id' => $request->guest_id, 'window_id' => $window];
                $palance = $this->generalinterface->guestBalance($palanceRequest);
            }
            if ($palance == 0) {
                $invoiceData = $this->invoiceInterface->store($palanceRequest);
                if ($invoiceData != null) {
                    $windowUpdate = $this->windowModel
                        ->where('id', $window)->where('guest_id', $request->guest_id)->update([
                            'invoice_number' => $invoiceData->first()->invoice_number
                        ]);
                    $dataForResult = [
                        'guest_id' => $request->guest_id,
                        'window_id' => $window,
                        'balance' => $palance,
                        'invoice_number' => $invoiceData->first()->invoice_number,
                        'window_status' => 'checked out',
                    ];
                    array_push($result, $dataForResult);
                } else {
                    $dataForResult = [
                        'guest_id' => $request->guest_id,
                        'window_id' => $window,
                        'balance' => $palance,
                        //'invoice_number' =>$invoiceData->first()->invoice_number,
                        'window_status' => 'Already Checked Out',
                    ];
                    array_push($result, $dataForResult);
                }
            } else {
                $dataForResult = [
                    'guest_id' => $request->guest_id,
                    'window_id' => $window,
                    'balance' => $palance,
                    'invoice_number' => null,
                    'window_status' => 'Not Checked Out',
                ];
                array_push($result, $dataForResult);
            }
        }

        $windowsIDsStatus = $this->windowModel->where('guest_id', $request->guest_id)
            ->where('invoice_number', null)->first();
        // dd($windowsIDsStatus);
        if ($windowsIDsStatus) {
            return $result;
        }
        $guestUpdate = $this->guestInhouseModel->where('id', $request->guest_id)->update([
            'checked_out' => 1,
            'out_date' => $settings->first()->hotel_date . ' ' . $timeOfNow,
            'checkout_by' => auth()->id(),
            'is_checked_in' => 0,
        ]);

        $this->taskDetailInterface->publicRefreshTask('dueOut', 1, 'due_out');


        $guestData =   $guestUpdate = $this->guestInhouseModel->where('id', $request->guest_id)->first('room_id');
        $room = $this->roomModel->where('id', $guestData->room_id)->update([
            'fo_status' => 'VA',
            'hk_stauts' => 'DI'
        ]);

        $this->changeRoomStatus($guestData->room_id, 'VA', 'By Check Out', 'DI');
        $deleteBreCharge = $this->preChargeModel->where('guest_id', $request->guest_id)->delete();
          //****************start shomoos */ 
          $api = 'https://masashomoos.masacloud.net/api/CheckOutAndRatingGuest';
          $guestHandeledData = $this->ShomoosIntegrationInterface->handelShomoosDataForStore($guestData,$api);
          $shomoosResponse = $this->shomoosInterface->store($guestHandeledData);

          //****************end shomoos */     
        if ($invoiceData) {
if($this->settings()->first()->ntmp_active) {
    $ntmp = $this->integrationForNTInterface->NTMPCreateOrUpdate($request->guest_id, 2, 3);

    $ntmp = $this->integrationForNTInterface->NTMPExpensesDetails($request->guest_id);
}
            return  $invoiceData->first()->invoice_number;
        } else {
            return false;
        }
    }

    public function storeInTransactionController($request)
    {
        $paymentTypeID = $this->payMentTypeModel->whereHas('modes', function ($q) {
            $q->where('name', 'cityledger');
        })->select('id')->first()->id;
        $dataForTransactio =
            [
                'guest_id' => $request['guest_id'],

                'room_id' => $request['room_id'],
                'hotel_date' => $request['hotel_date'],
                'window_id' => $request['window_id'][0],
                'payment_type_id' => $paymentTypeID,

                'payment_amount' => $request['payment_amount'],
                'trans_type' => 'REC',
                'recd_type' => 'P',
                'description' => 'city Ledger Checkout',
                'is_reservation' => '0',
                'inc' => 0,
            ];

        $transationsRequest = new Request;
        $transationsRequest->merge($dataForTransactio);
        $transation = $this->transactionController->store($transationsRequest);
        return $transation;
    }

    public function storeInCompanyStatementController($request)
    {
    }

    public function cityLedgerCheckout($request)
    {
        $transationData = [];
        //dd($request);
        $palance = $this->generalinterface->guestBalance($request);
        if ($palance == 0) {
            dd('palance =0');
            $request['balance'] = $palance;
            $this->checkOut($request);
            return true;
        } else {
            $tenantID = $this->tenantModel->current();
            if ($tenantID) {
                $hotelDate = cache()->get('settings_' . $tenantID->id)->first();
            } else {
                $hotelDate = $this->settingModel->first()->hotel_date;
            }


            $guestData = $this->guestInhouseModel->where('id', $request['guest_id'])->select('room_id', 'company_id')->with('company')->first();
            //  dd( ($guestData->company->current_balance +  $palance) > $guestData->company->max_balance);
            if ($guestData->company_id == null) {
                return 'noCompanySelected';
            }
            //  dd(($guestData->company->current_balance +  $palance) < $guestData->company->max_credit_limit);

            if (($guestData->company->current_balance +  $palance) < $guestData->company->max_credit_limit) {


                $transationData['payment_amount'] = $palance;
                $transationData['room_id'] = $guestData->room_id;
                $transationData['hotel_date'] = $hotelDate->hotel_date;
                $request->merge($transationData);
                //dd($request);
                $transactionReturn =  $this->storeInTransactionController($request);


                $invoiceNumber = $this->checkOut($request);

                //store in company statment controller
                $dateFrCompanyStatment = [
                    'invoice_no'                 => $invoiceNumber,

                    'guest_id'                   => $request['guest_id'],
                    'company_id'                 => $guestData->company_id,
                    'trans_date'                 => Carbon::parse($hotelDate->hotel_date)->format('Y/m/d'),
                    'trans_no'                   =>  $transactionReturn->original['transaction']->trans_no,
                    'trans_type'                 => 'INV',
                    'ref_no'                     =>  $transactionReturn->original['transaction']->trans_no,
                    'description'                => 'invoice',
                    'debit_amount'               =>  $palance,
                    'credit_amount'              => 0.0,
                    'created_by'                 => auth()->user()->id,
                    'paid_amount'                => 0,
                    'is_paid'                    => false,
                ];
                $companyStatmentRequest = new Request();
                $companyStatmentRequest->merge($dateFrCompanyStatment);
                $da = $this->companyStatementController->store($companyStatmentRequest);
                return true;
            } else {
                return 'Limt Not Enough';
            }
            return false;
        }
    }

    // public function cityLedgerCheckout($request)
    // {

    // }
    public function ReCheckin($request)
    {
        //dd('mm');
        $guestUpdate = $this->guestInhouseModel->where('id', $request->guest_id)->update([
            'checked_out'   => 0,
            'is_checked_in' => 1,
        ]);
        $guestData =  $this->guestInhouseModel->where('id', $request->guest_id)->first('room_id');
        $room = $this->roomModel->where('id', $guestData->room_id)->update([
            'fo_status' => 'OC',
            'hk_stauts' => 'DI'
        ]);
        //$deleteBreCharge = $this->preChargeModel->where('guest_id',$request->guest_id)->delete();

        return  true;
    }

    public function guestAttachment($request)
    {
        $path = 'storage/guest';
        $prefixName = 'guest';
        $fileName = $this->uploadFile($request, $path, $prefixName);
        if (is_array($request->file)) {
            foreach ($fileName as $file) {
                $createGuestAttachment = $this->guestAttachmentModel::create([
                    'guest_id' => $request->guest_id,
                    'attachment' => $file,
                ]);
            }
        } else {
            $createGuestAttachment = $this->guestAttachmentModel::create([
                'guest_id' => $request->guest_id,
                'attachment' => $fileName,
            ]);
        }
        $attachmentReturn = $this->guestAttachmentModel->where('guest_id', $request->guest_id)->get();
        if ($createGuestAttachment) {
            return $attachmentReturn;
        } else {
            return null;
        }
    }

    public function guestDeleteAttachment($id)
    {
        $attacheToDelete = $this->guestAttachmentModel->where('id', $id)->first();
        if ($attacheToDelete) {
            $file = $attacheToDelete->attachment;
            $attacheToDelete->delete();
            $fileName = $this->deleteFile($file);
            return true;
        } else {
            return false;
        }
    }

    // public function updateuGestAttachment($request,$id)
    // {
    //     $attacheToDelete = $this->guestAttachmentModel->where('id', $id)->first();
    //     if($attacheToDelete) {
    //         $file = $attacheToDelete->attachment;
    //         $attacheToDelete->delete();
    //         $fileName = $this->deleteFile($file);
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    public function updateuGestAttachment($request, $id)
    {
        $pastAttach = $this->guestAttachmentModel->where('id', $id)->first();
        if ($pastAttach) {
            $path = 'storage/guest';
            $prefixName = 'guest';
            $fileName = $this->uploadFile($request, $path, $prefixName, $pastAttach->attachment);
            $createGuestAttachment = $this->guestAttachmentModel::where('id', $id)->update([
                'guest_id' => $request->guest_id,
                'attachment' => $fileName,
            ]);
            if ($createGuestAttachment) {
                return true;
            } else {
                return null;
            }
        }
    }
   
    public function GetOcGuestByDate($request)
    {
        $ocGuest = $this->guestInhouseModel
        ->whereDate('in_date','<=', Carbon::parse($request->date))
        ->whereDate('out_date','>=',Carbon::parse($request->date))
        ->where('is_cancel',false)->where('checked_out',false)->get();
        return $ocGuest;
    }
}
