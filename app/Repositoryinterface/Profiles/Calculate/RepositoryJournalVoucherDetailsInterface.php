<?php
namespace App\Repositoryinterface\Profiles\Calculate;
interface RepositoryJournalVoucherDetailsInterface{

    public function index();
    public function store($date,$masterID);
    public function show($year);
    public function update($date,$id);
    public function destroy($id);

}