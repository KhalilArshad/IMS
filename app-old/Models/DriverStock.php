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
    public function driver()
    {
        return $this->belongsTo('App\Models\Driver', 'driver_id');
    }
    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id')->with('unit');
    }
}
