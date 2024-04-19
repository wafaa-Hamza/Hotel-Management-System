<?php

namespace App\Models;

use Coderflex\LaravelTicket\Models\Ticket as TicketModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Ticket extends TicketModel
{
    use SoftDeletes;
    use LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Ticket has been {$eventName}");
    }
    protected $dates = ['deleted_at'];
    public function ticketFiles()
    {
        return $this->hasMany(TicketFile::class, 'ticket_id', 'id');
    }
}
