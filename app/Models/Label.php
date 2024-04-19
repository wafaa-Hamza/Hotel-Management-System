<?php

namespace App\Models;

use Coderflex\LaravelTicket\Models\Label as TicketLabel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Label extends TicketLabel
{
    use SoftDeletes;
    use LogsActivity;
    protected $dates = ['deleted_at'];
    protected $casts = [
        'is_visible' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Ticket Label has been {$eventName}");
    }
    public static function boot()
    {
        parent::boot();

        static::saving(function (Label $category) {
            $category->slug = Str::slug($category->name);
        });
    }
}
