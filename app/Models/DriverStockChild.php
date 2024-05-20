<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverStockChild extends Model
{
    use HasFactory;
    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id')->with('unit');
    }
}
