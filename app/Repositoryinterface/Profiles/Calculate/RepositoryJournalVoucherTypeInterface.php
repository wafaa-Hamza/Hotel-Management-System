<?php
namespace App\Repositoryinterface\Profiles\Calculate;
interface RepositoryJournalVoucherTypeInterface{

    public function index();
    public function store($date);
    public function show($year);
    public function update($date,$id);
    public function destroy($id);

}