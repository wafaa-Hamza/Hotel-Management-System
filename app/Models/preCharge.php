<?php

namespace App\Models;

use App\Models\User;
use App\Models\Guests;
use App\Models\Ledger;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class preCharge extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function ledger()
    {
       return $this->belongsTo(Ledger::class,'ledger_id','id');
    }

   public function guest()
   {
    return $this->belongsTo(Guests::class,'guest_id','id');
   }
   public function user()
   {
    return $this->belongsTo(User::class,'guest_id','id');
   }
}
