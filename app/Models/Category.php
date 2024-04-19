<?php

namespace App\Models;

use Coderflex\LaravelTicket\Models\Category as TicketCategory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends TicketCategory
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
            ->setDescriptionForEvent(fn (string $eventName) => "Ticket category has been {$eventName}");
    }
    public static function boot()
    {
        parent::boot();

        static::saving(function (Category $category) {
            $category->slug = Str::slug($category->name);
        });
    }
}
