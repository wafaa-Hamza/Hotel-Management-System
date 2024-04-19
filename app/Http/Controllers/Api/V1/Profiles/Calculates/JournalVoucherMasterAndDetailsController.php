<?php

namespace App\Http\Controllers\Api\V1\Profiles\Calculates;

use App\helpers;
use App\Models\JournalVoucherMaster;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Sabberworm\CSS\Settings;
use Spatie\Multitenancy\Models\Tenant;
use App\Http\Resources\GeneralCollection;
use App\Http\Resources\JournalVoucherMasterResource;
use App\Http\Resources\JournalVoucherDetailsResource;
use App\Http\Requests\JournalVoucherMasterAndDetailsRequest;
use App\Repositoryinterface\Profiles\Calculate\RepositoryJournalVoucherMasterInterface;
use App\Repositoryinterface\Profiles\Calculate\RepositoryJournalVoucherDetailsInterface;


class JournalVoucherMasterAndDetailsController extends Controller
{
    use helpers;
    private $jurnalVoucherMasterInterface;
    private $jurnalVoucherDetailsInterface;

    public function __construct(RepositoryJournalVoucherMasterInterface $jurnalVoucherMasterInterface, RepositoryJournalVoucherDetailsInterface $jurnalVoucherDetailsInterface)
    {
        $this->jurnalVoucherMasterInterface = $jurnalVoucherMasterInterface;
        $this->jurnalVoucherDetailsInterface = $jurnalVoucherDetailsInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
//        $this->authorize('view-journal voucher');

        $jurnalVoucher = $this->jurnalVoucherMasterInterface->index();
        if ($jurnalVoucher->first()) {
            return  $this->apiResponse(new GeneralCollection($jurnalVoucher, JournalVoucherMasterResource::class));
        } else {
            return response()->json(['message' => 'Not Found'], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JournalVoucherMasterAndDetailsRequest $request)
    {


//        $this->authorize('create-journal voucher');


        $jurnalVoucherMaster = $this->jurnalVoucherMasterInterface->store($request);
        if ($jurnalVoucherMaster) {
            $jurnalVoucherDetails = $this->jurnalVoucherDetailsInterface->store($request, $jurnalVoucherMaster);
            $jurnalVoucher = $this->jurnalVoucherMasterInterface->show($jurnalVoucherMaster);
            // dd($jurnalVoucher);
            return  $this->apiResponse(new GeneralCollection($jurnalVoucher, JournalVoucherMasterResource::class));
            // return $jurnalVoucher;
        } else {
            return response()->json(['message' => 'Not Found'], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
//        $this->authorize('view-journal voucher');

        $jurnalVoucher = $this->jurnalVoucherMasterInterface->show($id);
        if ($jurnalVoucher->first()) {
            //  return $jurnalVoucher;
            return  $this->apiResponse(new GeneralCollection($jurnalVoucher, JournalVoucherMasterResource::class));
        } else {
            return response()->json(['message' => 'Not Found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JournalVoucherMasterAndDetailsRequest $request, string $id)
    {

//        $this->authorize('edit-journal voucher');

        $jurnalVoucherMaster = $this->jurnalVoucherMasterInterface->update($request, $id);
        if ($jurnalVoucherMaster) {
            $jurnalVoucherDetails = $this->jurnalVoucherDetailsInterface->destroy($id);
            $jurnalVoucherDetails = $this->jurnalVoucherDetailsInterface->store($request, $id);
            $jurnalVoucher = $this->jurnalVoucherMasterInterface->show($id);
            // dd($jurnalVoucher);
            return  $this->apiResponse(new GeneralCollection($jurnalVoucher, JournalVoucherMasterResource::class));
            // return $jurnalVoucher;
        } else {
            return response()->json(['message' => 'Not Found'], 404);
        }




        return $jurnalVoucher = $this->store($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
//        $this->authorize('delete-journal voucher');

        $jurnalVoucherMaster = $this->jurnalVoucherMasterInterface->destroy($id);
        if ($jurnalVoucherMaster) {
            $jurnalVoucherMaster = $this->jurnalVoucherDetailsInterface->destroy($id);
            return response()->json(['message' => 'Deleted'], 200);
        } else {
            return response()->json(['message' => 'notFound']);
        }
    }

    public function Accounts_Print_journal_voucher(Request $request)

    {


        $accounts_print_journal_voucher = JournalVoucherMaster::with(['journalVoucherDetails.chartOfAccount', 'createdBy', 'journalVoucherType'])
            ->where('jv_no', $request->jv_id)->get();
        //       dd('here');
        $tenant = Tenant::current();
        //          dd($tenant->id);


        $settings = cache()->get('settings_' . $tenant->id)->first();
        //        $settings = Settings::first();
        $journal_voucher_settings = $settings->only(['name', 'name_loc,', 'phone', 'email', 'address', 'city', 'vat_no']);


        $tenant = Tenant::current();

        $settings = cache()->get('settings_' . $tenant->id)->first();

        $journal_voucher_settings = $settings->only(['name', 'name_loc,', 'phone', 'email', 'address', 'city', 'vat_no']);


        return response()->json([
            'data' => [
                'Settings'               => $journal_voucher_settings,
                'Journal_Voucher_Print'  => $accounts_print_journal_voucher
            ]
        ]);
    }
}
