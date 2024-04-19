<?php

namespace App\Models;

use App\Models\Window;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChargeRouted extends Model
{
    use HasFactory;
    protected $fillable = [
        'guestID_to',
        'ledgerID',
        'guestID_from',
        'window_id_from',
        'window_id_to',
    ];

    public function guestTo()
    {
        return $this->belongsTo(Guests::class,'guestID_to','id');
    }
    public function guestFrom()
    {
        return $this->belongsTo(Guests::class,'guestID_from','id');
    }
    public function ledger()
    {
        return $this->belongsTo(Ledger::class,'ledgerID','id');
    }
    public function windowFrom()
    {
        return $this->belongsTo(Window::class,'window_id_from','id');
    }
    public function windowTo()
    {
        return $this->belongsTo(Window::class,'window_id_to','id');
    }
}
