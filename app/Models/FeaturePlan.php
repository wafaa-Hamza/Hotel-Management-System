<?php

namespace App\Models;

use App\Models\Feature;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FeaturePlan extends Pivot
{
    protected $connection = 'landlord';
    protected $fillable = [
        'charges',
    ];

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plas::calss);
    }
}
