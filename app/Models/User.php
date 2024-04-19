<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Coderflex\LaravelTicket\Concerns\HasTickets;
use Coderflex\LaravelTicket\Contracts\CanUseTickets;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use LucasDotVin\Soulbscription\Models\Concerns\HasSubscriptions;

class User extends Authenticatable
implements CanUseTickets
{

    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasSubscriptions;
    use SoftDeletes;
    use Notifiable;

    use LogsActivity;
    use HasTickets;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()

            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "User has been {$eventName}");
    }
    protected $dates = ['deleted_at'];
    protected static $logName = 'user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'phonenumber',
        'email',
        'password',
        'role',
        'language',

    ];
    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }


    public function getIsCashierOpenAttribute()
    {
        $app = app('settings');
        $hotel_date = $app->hotel_date;
        return  $is_open = Cashier::where('user_id', auth()->user()->id)->whereDate('hotel_date', $hotel_date)->where('status', 1)->count();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function cashiers()
    {
        return $this->hasMany(Cashier::class, 'user_id', 'id');
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'created_by', 'id');
    }
    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role');
    }
}
