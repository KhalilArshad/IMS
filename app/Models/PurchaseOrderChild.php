<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderChild extends Model
{
    use HasFactory;
    public function items()
    {
        return $this->belongsTo('App\Models\Item', 'item_id')->with('unit');
    }
}
