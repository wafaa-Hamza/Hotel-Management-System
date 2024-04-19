<?php

namespace App\Models;

use App\Models\Feature;
use Illuminate\Database\Eloquent\Model;
//use LucasDotVin\Soulbscription\Models\Concerns\Expires;

class FeatureTicket extends Model
{
  //  use Expires;

    protected $connection = 'landlord';
    protected $fillable = [
        'charges',
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
