<?php

namespace App\Models;

use App\Models\companyProfile;
use App\Models\Guests;
use App\Models\Payment_type;
use App\Models\User;
use App\Models\Window;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Statement extends Model
{

   use SoftDeletes;
   use LogsActivity;
   use HasFactory;



   protected $guarded = [];
   protected $table = 'company_AR_statements';


   public function getActivitylogOptions(): LogOptions
   {
      return LogOptions::defaults()
         ->logAll()->logOnlyDirty()
         ->setDescriptionForEvent(fn (string $eventName) => "Statement has been {$eventName}");
   }

   public function companyProfile()
   {
      return $this->hasOne(companyProfile::class, 'id', 'company_id');
   }


   public function users()
   {
      return $this->hasMany(User::class, 'id', 'created_by');
   }
   public function paymentTypes()
   {
      return $this->hasMany(Payment_type::class, 'id', 'payment_type_id');
   }
   public function Guest()
   {
      return $this->hasOne(Guests::class, 'id', 'guest_id');
   }
   public function windows()
   {
      return $this->hasMany(Window::class, 'invoice_no', 'invoice_no');
   }
}
