<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Language extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $fillable=['lang_locale'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['id','lang_locale'])
            ->setDescriptionForEvent(fn(string $eventName) => "Language has been {$eventName}");
    }
    public function users()
    {
        return $this->hasMany(User::class,'language_id','id');
    }
}
