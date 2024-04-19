<?php

namespace App\Models;

use App\Models\Ledger;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class closeSales extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;
    protected $fillable = ['hotel_date', 'sys_date', 'ledger_id', 'payment_type_id', 'charge_amount', 'Payment_amount'];
    protected $table  = 'day_close_sales';
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "closeSales has been {$eventName}");
    }
    public function ledger()
    {
        return $this->hasOne(Ledger::class, 'id', 'ledger_id');
    }
    public function  Payment_type()
    {
        return $this->hasOne(Payment_type::class, 'id', 'payment_type_id');
    }
}
