<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
//use LucasDotVin\Soulbscription\Models\Concerns\HandlesRecurrence;

use App\Models\Plan;
use App\Models\FeaturePlan;
use App\Models\FeatureTicket;


class Feature extends Model
{
    //use HandlesRecurrence;
    use HasFactory;
    use SoftDeletes;

protected $connection = 'landlord';
    protected $fillable = [
        'consumable',
        'name',
        'periodicity_type',
        'periodicity',
        'quota',
        'postpaid',
    ];

    public function plans()
    {
        return $this->belongsToMany(plan::class)
            ->using(FeaturePlan::class);
    }

    public function tickets()
    {
        return $this->hasMany(FeatureTicket::class);
    }
}

