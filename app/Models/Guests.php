<?php

namespace App\Models;

use App\Models\Market;
use App\Models\Window;
use App\Models\MoreName;
use App\Models\RateCode;
use App\Models\RoomType;
use App\Models\ResRateDay;
use App\Models\MealPackage;
use App\Traits\Searchable;
use Illuminate\Support\Str;
use App\Models\guest_profile;
use App\Models\companyProfile;
use App\Models\SourceBusiness;
use App\Models\GuestAttachment;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use App\Models\ExtraBedAndMealPivotGuest;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guests extends Model
{

    use LogsActivity;
    use HasFactory;
    use SoftDeletes;
    use Searchable;

    protected $fillable = [
        'folio_no',
        'in_date',
        'out_date',
        'original_out_date',
        'no_of_nights',
        'rm_rate',
        'taxes',
        'no_of_pax',
        'hotel_note',
        'client_note',
        'way_of_payment',
        'profile_id',
        'company_id',
        'room_id',
        'room_type_id',
        'rate_code_id',
        'market_segment_id',
        'source_of_business_id',
        'meal_id',
        'meal_package_id',
        'created_by',
        'created_inhousGuest_at',
        'checked_out',
        'checkout_by',
        'checkout_at',
        'is_reservation',
        'res_status',
        'is_group',
        'group_code',
        'is_dummy',
        'Is_PM',
        'is_cancel',
        'cancel_date',
        'is_checked_in',
        'res_no',
        'res_status',
        'ref_no',
        'is_online',
        'is_join',
        'is_connected',
        'join_to',  //that have id of room master
        'meal_package_id',
        'vat_no',
        'inv_address',
        'company_name',
        'vip',
        'locked',
        'res_type',
        'scth_transaction_id',
        'customer_type',
        'purpose_of_visit',
    ];
    protected $searchable = [
        'in_date',
        'out_date',
        'room_type_id',
        'company_id',
        'checkout_at',
        'profile.first_name',
        'profile.last_name',
        'room.rm_name_en',
        'room.rm_name_loc',
        'rateCode.rate_code',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->logAll()->logOnlyDirty()
            ->useLogName('Reservation')
            ->logExcept(['updated_at', 'created_at'])
            ->setDescriptionForEvent(fn (string $eventName) => " Reservation has been {$eventName}");
    }

    public function profile()
    {
        return $this->belongsTo(guest_profile::class, 'profile_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(companyProfile::class, 'company_id', 'id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id', 'id');
    }

    public function rateCode()
    {
        return $this->belongsTo(RateCode::class, 'rate_code_id', 'id');
    }

    public function marketSegment()
    {
        return $this->belongsTo(Market::class, 'market_segment_id', 'id');
    }

    public function sourceBusiness()
    {
        return $this->belongsTo(SourceBusiness::class, 'source_of_business_id', 'id');
    }
    public function meal()
    {
        return $this->belongsTo(Meal::class, 'meal_id', 'id');
    }
    public function meal_package()
    {
        return $this->belongsTo(MealPackage::class, 'meal_package_id', 'id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function checkout_by()
    {
        return $this->belongsTo(User::class, 'checkout_by', 'id');
    }
    public function resRateDay()
    {
        return $this->hasMany(ResRateDay::class, 'guest_id', 'id');
    }
    public function window()
    {
        return $this->hasMany(Window::class, 'guest_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'guest_id', 'id');
    }

    public function extendStay()
    {
        return $this->hasMany(ExtendStay::class, 'guest_id', 'id');
    }
    public function reservation_status()
    {
        return $this->hasOne(ReservationStatus::class, 'res_status_code', 'res_status');
    }
    public function moreName()
    {
        return $this->hasMany(MoreName::class, 'guest_id', 'id');
    }
    public function preCharges()
    {
        return $this->hasMany(preCharge::class, 'guest_id', 'id');
    }
    public function attachment()
    {
        return $this->hasMany(GuestAttachment::class, 'guest_id', 'id');
    }
    public function ExtraBedMeal()
    {
        return $this->hasMany(ExtraBedAndMealPivotGuest::class, 'guest_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Guests::class, 'group_code', 'group_code')
            ->where('room_type_id', '!=', 1);
    }
    public function parent()
    {
        return $this->belongsTo('group_code', 'group_code');
    }

    public function LogActivity()
    {
        return $this->hasMany(Activity::class, 'subject_id', 'id')
            ->where('subject_type', 'App\Models\Guests');
    }

    public function groupDetail()
    {
        return $this->belongsTo(GroupDetail::class,'id','guest_id');
    }
}
