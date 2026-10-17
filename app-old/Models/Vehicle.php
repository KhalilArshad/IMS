<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 
        'registration_no', 
        'driver_id', 
        'price', 
        'modal', 
    ];
    public function driver()
    {
        return $this->belongsTo('App\Models\Driver', 'driver_id');
    }
}
