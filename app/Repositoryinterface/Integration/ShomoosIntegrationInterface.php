<?php
namespace App\Repositoryinterface\Integration;

interface ShomoosIntegrationInterface{
    public function getNationality($request);
    public function handelShomoosData($guestData);
    public function handelShomoosDataForStore($guestData,$api);
    public function InsertGuest($shomoosTableRowInserted);
    public function CheckOutAndRatingGuest($request,$guestData);

   // public function storeInShomoos($guestData,$api);
   
}
