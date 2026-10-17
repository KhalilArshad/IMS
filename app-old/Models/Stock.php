<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_id', 
        'quantity', 
        'unit_price', 
        'sale_price'
    ];
    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id')->with('unit');
    }

}
