<?php

namespace App\Models;

use App\Models\Feature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

//use LucasDotVin\Soulbscription\Models\Concerns\Expires;
class FeatureConsumption extends Model
{
   // use Expires;
    use HasFactory;

    protected $connection = 'landlord';
    protected $fillable = [
        'consumption',
        'expired_at',
    ];

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }

    public function subscriber()
    {
        return $this->morphTo('subscriber');
    }
}
