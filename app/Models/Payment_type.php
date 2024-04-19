<?php

namespace App\Models;

use App\Models\PaymentMode;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment_type extends Model
{
    use SoftDeletes;
    use LogsActivity;
    use HasFactory;
    protected $table = 'payment_types';
    protected $guarded = [];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Payment_type has been {$eventName}");
    }

    public function modes()
    {
        return $this->hasOne(PaymentMode::class, 'id', 'payment_modes_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'payment_type_id', 'id');
    }
}
