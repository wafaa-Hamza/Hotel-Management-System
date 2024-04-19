<?php

namespace App\Models;

use App\Models\User;
use App\Models\Guests;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExtendStay extends Model
{
    use LogsActivity;
    use HasFactory;
    use SoftDeletes;
    protected $table = 'extends_stay';
    protected $fillable = [
        'guest_id',
        'out_date_from',
        'out_date_to',
        'done_by'
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "ExtendStay has been {$eventName}");
    }

    public function guests()
    {

        return $this->hasMany(Guests::class, 'id', 'guest_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id', 'done_by');
    }
}
