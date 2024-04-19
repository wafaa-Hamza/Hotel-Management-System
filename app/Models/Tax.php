<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tax extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Tax has been {$eventName}");
    }
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name', 'name_loc', 'per', 'amount', 'formula', 'extra', 'accept_per'
    ];

    public function transactions_taxes()
    {
        return $this->hasMany(TransactionTaxes::class, 'tax_id', 'id');
    }
}
