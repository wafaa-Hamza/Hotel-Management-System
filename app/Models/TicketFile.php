<?php

namespace App\Models;

use App\Models\Ticket;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketFile extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;

    protected $fillable = ['file', 'ticket_id'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "TicketFile has been {$eventName}");
    }

    protected $dates = ['deleted_at'];
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
