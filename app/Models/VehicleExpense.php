<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleExpense extends Model
{
    use HasFactory;
    protected $table = 'vehicle_expenses';

    protected $fillable = ['driver_id', 'vehicle_id', 'amount', 'date', 'remarks'];

    protected $casts = [
        'date' => 'date',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
    public function vehicle()
    {
        return $this->belongsTo('App\Models\Vehicle', 'vehicle_id')->with('driver');
    }
}
