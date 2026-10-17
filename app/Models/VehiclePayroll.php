<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehiclePayroll extends Model
{
    protected $fillable = [
        'vehicle_id',
        'date',
        'month',
        'amount',
        'advance',
        'add_in_remaining',
        'paid_in_advance',
        'overtime',
        'current_paid',
        'remaining',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
