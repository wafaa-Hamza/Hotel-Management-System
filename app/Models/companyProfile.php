<?php

namespace App\Models;

use App\Models\Guests;

use App\Models\Country;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class companyProfile extends Model
{
    //use SoftDeletes;
    use LogsActivity;
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];


    public function profiles()
    {
        return $this->hasOne(Country::class, 'country_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "companyProfile has been {$eventName}");
    }
    public static function CalcBalance($id)
    {

        $debit = Statement::where('company_id', $id)
            ->where('void', 0)
            ->orWhereNull('void')
            ->sum('debit_amount');

        $credit = Statement::where('company_id', $id)
            ->where('void', 0)
            ->orWhereNull('void')
            ->sum('credit_amount');

        $calculate = $debit - $credit;

        companyProfile::where('id', $id)->update([

            'company_balance' => $calculate
        ]);
        return  $calculate;
    }
    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }
    public function guest()
    {
        return $this->hasMany(Guests::class, 'company_id', 'id');
    }
}
