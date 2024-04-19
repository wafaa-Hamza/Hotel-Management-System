<?php

namespace App\Repository\profiles\guestProfile;

use App\helpers;
use App\Models\Expenses;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryExpensesInterface;
use Illuminate\Support\Facades\Storage;
use Spatie\Multitenancy\Models\Tenant;

class DBrepositoryExpenses implements RepositoryExpensesInterface
{
    use helpers;
    private $expensesModel;
    private $tenantModel;
    private $validation;
    public function __construct(Expenses $expensesModel, Tenant $tenantModel)
    {
        $this->expensesModel = $expensesModel;
        $this->tenantModel = $tenantModel;
    }

    public function index()
    {
        $expenses = $this->expensesModel::get();
        return $expenses;
    }
    public function show($id)
    {
        $expenses = $this->expensesModel::where('id', $id)->first();
        return $expenses;
    }

    public function store($request)
    {
        $prefixName = 'expenses';
        $path = 'storage/expensesFile';

        if ($request->has('file')) {
            $fileNanme = $this->uploadFile($request, $path, $prefixName);
            $filename = '/' . $fileNanme;
        } else {
            $filename = null;
        }
        $settings = $this->settings()->first();
        $tenant = $this->tenantModel::current();
        $expenses = $this->expensesModel::create([
            'name' => (!empty($request->name)) ? $request->name : null,
            'reference' => (!empty($request->reference)) ? $request->reference : null,
            'exp_ledger_id' => $request->exp_ledger_id,
            'amount' => $request->amount,
            'file' => $filename,
            'hotel_date' => $settings->hotel_date,
            'description' => $request->description,
        ]);
        return $expenses;
    }
    public function update($request, $id)
    {
        $epxpenses = $this->show($id);
        if ($request->has('file')) {
            $prefixName = 'expenses';
            $path = 'storage/expensesFile';
            $fileNanme = $this->uploadFile($request, $path, $prefixName, $epxpenses->file);
        }

        if ($epxpenses) {
            $this->expensesModel::where('id', $id)->update([
                'exp_ledger_id' => (!empty($request->exp_ledger_id)) ? $request->exp_ledger_id : $epxpenses->exp_ledger_id,
                'amount' => (!empty($request->amount)) ? $request->amount : $epxpenses->amount,
                'description' => ($request->has('description')) ? $request->description : $epxpenses->description,
                'name' => ($request->has('name')) ? $request->name : $epxpenses->name,
                'reference' => ($request->has('reference')) ? $request->reference : $epxpenses->reference,
                'file' => ($request->has('file')) ? $fileNanme : $epxpenses->file,
            ]);

            $epxpenses = $this->show($id);

            return $epxpenses;
        } else {
            return null;
        }
    }

    public function destroy($id)
    {

        $epxpenses = $this->show($id);

        if ($epxpenses) {
            $dataDeleted = $epxpenses->delete();

            if ($epxpenses->file != null) {
                Storage::delete($epxpenses->file);
            }

            return $dataDeleted;
        } else {
            return null;
        }
    }
}
