<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use App\Models\Transaction;
use App\Models\Payment_type;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMode extends Model
{
    use HasFactory;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "PaymentMode has been {$eventName}");
    }

    public function payment_types()
    {
        return $this->hasMany(Payment_type::class, 'payment_modes_id', 'id');
    }
    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class, Payment_type::class, 'payment_modes_id', 'payment_type_id','id');
    }
}
