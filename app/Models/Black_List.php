<?php

namespace App\Models;

use App\Models\guest_profile;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Black_List extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;
    protected $table = 'black_lists';

    protected $guarded = ['id'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => " Black_List has been {$eventName}");
    }
    public function profiles()
    {
        return $this->hasMany(guest_profile::class, 'id', 'profile_id');
    }
    public function users()
    {
        return $this->hasMany(User::class, 'id', 'return_by');
    }
}
