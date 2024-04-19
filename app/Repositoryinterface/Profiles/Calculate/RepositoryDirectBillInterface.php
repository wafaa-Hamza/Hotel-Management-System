<?php
namespace App\Repositoryinterface\Profiles\Calculate;
interface RepositoryDirectBillInterface{

    public function directBill($request);
    public function getDataForDirectBill();
}