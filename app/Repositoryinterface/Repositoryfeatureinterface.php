<?php
namespace App\Repositoryinterface;

interface Repositoryfeatureinterface{
    public function index();
    public function featurePagination();
    public function store($request);
    public function show($id);
    public function update($request, $id);
    public function destroy($id);
    public function geSoftDeletedData();
    public function restorDataTrashed($id);
    public function DBDelete($id);
}
