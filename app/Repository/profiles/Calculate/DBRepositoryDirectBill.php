<?php

namespace App\Repository\profiles\Calculate;

use App\Models\Tax;
use Illuminate\Http\Request;
use App\Http\Controllers\TransactionController;
use App\Models\Guests;
use App\Models\Setting;
use App\Models\Window;
use Symfony\Component\CssSelector\XPath\Extension\FunctionExtension;
use App\Repositoryinterface\Profiles\Calculate\RepositoryDirectBillInterface;
use Spatie\Multitenancy\Models\Tenant;

class DBRepositoryDirectBill implements RepositoryDirectBillInterface
{
    private $guestModel;
    private $tenantModel;
    private $settingModel;
    private $windowModel;
    private $transactionController;

    public function __construct(Guests $guestModel, Tenant $tenantModel, Setting $settingModel, Window $windowModel, TransactionController $transactionController)
    {
        $this->guestModel = $guestModel;
        $this->tenantModel = $tenantModel;
        $this->settingModel = $settingModel;
        $this->windowModel = $windowModel;
        $this->transactionController = $transactionController;
    }

    public function directBill($request)
    {
        $getDataForDirectBill = $this->getDataForDirectBill();
        $roomID =
            $getDataForDirectBill->merge($request->toArray());
        // dd($getDataForDirectBill);
        $request = $getDataForDirectBill;
        $data = $getDataForDirectBill->all();
        $ledger_id = $request->ledger_id;
        $charge_amount = $request->amount;
        $amount = $request->amount;
        $data['ledger_id'] = null;
        $data['charge_amount'] = null;
        $data['amount'] = null;
        $data['payment_amount'] = $amount;
        $request->replace($data);
        // dd($request);
        $storeInTaxesWithLedgerID = $this->transactionController->store($request);
        $data['ledger_id'] = $ledger_id;
        $data['charge_amount'] = $charge_amount;
        $data['payment_type_id'] = null;
        $data['payment_amount'] = null;
        $data['charge_amount'] = $amount;
        $request->replace($data);
        //dd( $request);
        $storeInTaxesWithLedgerID = $this->transactionController->store($request);
        //dd($storeInTaxesWithLedgerID);

        if ($storeInTaxesWithLedgerID && $storeInTaxesWithLedgerID) {
            return $storeInTaxesWithLedgerID;
        } else {
            return false;
        }
        //    $storeInTaxesWithLedgerID = $this->transactionController->store($request->except('paymentType'));
        //    $storeInTaxesWithPaymentType = $this->transactionController->store($request->except('ledgerID'));
    }

    public function getDataForDirectBill()
    {
        $data = [];
        $directBillGuestID = $this->guestModel->where('folio_no', 'directBill')->select('id', 'room_id')->first();
        //dd($directBillGuestID);
        $data['guest_id'] =  $directBillGuestID->id;
        $data['room_id'] =  $directBillGuestID->room_id;
        $data['res_id'] =  null;
        $tenantCurrentID = $this->tenantModel::current();
        if ($tenantCurrentID) {
            $hotelData = cache()->get('settings_' . $tenantCurrentID->id)->first();
            dd($hotelData);
            if ($hotelData) {
                $data['hotel_date'] = $hotelData->hotel_date;
            } else {
                $data['hotel_date'] = $this->settingModel->select('hotel_date')->first()->hotel_date;
            }
        } else {
            $data['hotel_date'] = $this->settingModel->select('hotel_date')->first()->hotel_date;
        }
        $data['window_id'] = $this->windowModel->where('window_name', 'directBill')->select('id')->first()->id;
        $data['trans_type'] = 'trans_type';
        $data['recd_type'] = 'recd_type';
        $data['ref_id'] = null;
        $data['is_reservation'] = false;
        $data['inc'] = true;
        $request = new Request;
        $request->merge($data);
        return $request;
    }
}
