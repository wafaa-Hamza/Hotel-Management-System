<?php

namespace App\Models;

use App\Models\DetailsIntegration_Table;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterIntegrationTable extends Model
{
    use SoftDeletes;
    use HasFactory;
    use LogsActivity;
    protected $guarded = ['id'];

    protected $table = 'master_integration_tables';

    public function details_table()
    {
        return $this->hasMany(DetailsIntegration_Table::class, 'master_id', 'id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => " MasterIntegrationTable has been {$eventName}");
    }
}
