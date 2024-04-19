<?php

namespace App\Models;

use App\Models\User;
use App\Models\JournalVoucherType;
use Spatie\Activitylog\LogOptions;
use App\Models\JournalVoucherDetail;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JournalVoucherMaster extends Model
{
    use LogsActivity, HasFactory;

    protected $guarded = ['id'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Floor has been {$eventName}");
    }

    public function journalVoucherType()
    {
        return $this->belongsTo(JournalVoucherType::class, 'jv_type_id', 'id');
    }
    public function journalVoucherDetails()
    {
        return $this->hasMany(JournalVoucherDetail::class, 'jv_master_id', 'id');
    }

    public function lastUpdatedUser()
    {
        return $this->belongsTo(User::class, 'last_updated_user_id', 'id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function postedUser()
    {
        return $this->belongsTo(User::class, 'id', 'Posted_User_Id');
    }
}
