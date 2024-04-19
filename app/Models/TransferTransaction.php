<?php

namespace App\Models;

use App\Models\Guests;
use App\Models\Window;
use App\Models\Transaction;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransferTransaction extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "TransferTransaction has been {$eventName}");
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id', 'trans_id');
    }
    public function trf_from_guest()
    {
        return $this->hasMany(Guests::class, 'id', 'trf_from_guest_id');
    }
    public function trf_to_guest()
    {
        return $this->hasMany(Guests::class, 'id', 'trf_to_guest_id');
    }
    public function trf_from_window()
    {
        return $this->hasOne(Window::class, 'id', 'trf_from_window_id');
    }
    public function trf_to_window()
    {
        return $this->hasOne(Window::class, 'id', 'trf_to_window_id');
    }
    public function users()
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }
}
