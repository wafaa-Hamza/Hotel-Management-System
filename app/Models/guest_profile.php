<?php

namespace App\Models;

use App\Models\User;
use App\Models\Guests;
use App\Models\Country;
use App\Traits\Searchable;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class guest_profile extends Model

{

    use HasFactory, SoftDeletes, LogsActivity, Searchable;


    protected $fillable = [
        'Profile_no',
        'group_code',
        'first_name',
        'last_name',
        'id_no',
        'mobile',
        'phone',
        'email',
        'address',
        'country_id',
        'city',
        'language',
        'date_of_birth',
        'gender',
        'created_by',
        'updated_by',
        'deleted_at',
        'deleted_at',
    ];




    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "guest_profile has been {$eventName}");
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function user_updated()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
    public function guest()
    {
        return $this->hasMany(Guests::class, 'profile_id', 'id');
    }
    public function idType()
    {
        return $this->hasOne(IdType::class, 'id', 'id_type_id');
    }

    // public function created_by_in_landloard() {
    //     return $this->setConnection('landloard')->belongsTo(User::class,'id','created_by');
    // }

}
