<?php
namespace App\Repositoryinterface\Profiles\Calculate;
interface RepositoryBudgetInterface{

    public function index();
    public function store($date);
    public function show($year);
    public function update($date);
    public function destroy($id);


    

}