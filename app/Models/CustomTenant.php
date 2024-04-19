<?php

namespace App\Models;

use App\Models\Subscription;
use App\Traits\HasSubscriptions;
//use LucasDotVin\Soulbscription\Models\Concerns\HasSubscriptions;
use Spatie\Multitenancy\Models\Tenant;
//use Spatie\Multitenancy\Models\Tenant as SpatieTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomTenant extends Tenant
{
    use HasSubscriptions;
    protected $guarded = ['id'];
    protected $table = 'tenants';
    public function subscription()
    {
        return $this->morphMany(Subscription::class, 'subscriber');
    }
}
