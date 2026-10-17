<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;
    protected $fillable = ['driver_id', 'date', 'total', 'status'];
    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier', 'supplier_id');
    }
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
}
