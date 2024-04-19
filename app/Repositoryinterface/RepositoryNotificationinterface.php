<?php
namespace App\Repositoryinterface;

interface RepositoryNotificationinterface{
    public function index();
    public function getLast5();
    public function store($request);
    public function show($id);
    public function update($request, $id);
    public function destroy($id);
}
