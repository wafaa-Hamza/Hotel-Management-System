<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailsIntegration_Table extends Model
{
    use SoftDeletes;
    use HasFactory;
    use LogsActivity;
    protected $guarded = ['id'];
    protected $table = 'details_integration_tables';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => " DetailsIntegration_Tabl has been {$eventName}");
    }
    public function master()
    {
        return $this->belongsTo(MasterIntegrationTable::class,'master_id','id');
    }
}
