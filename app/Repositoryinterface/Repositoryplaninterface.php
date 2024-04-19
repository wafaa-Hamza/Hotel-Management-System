<?php
namespace App\Repositoryinterface;

interface Repositoryplaninterface{
    public function index();
    public function planPagination();
    public function store($request);
    public function show($id);
    public function update($request, $id);
    public function destroy($id);
    public function createPlanFeature($request, $planID);
    public function geSoftDeletedData();
    public function restorDataTrashed($id);
    public function DBDelete($id);
    public function createPlanForTenant($tenantID,$planID,$expired_at,$started_at,$was_switched);
   
}
