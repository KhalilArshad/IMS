<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverStock extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_id', 
        'driver_id', 
        'current_stock', 
        'purchase_price'
    ];
}
