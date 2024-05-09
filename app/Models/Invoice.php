<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 
        'driver_id', 
        'status', 
        'total_bill', 
        'discount', 
        'total_after_discount', 
        'paid_amount', 
        'remaining', 
        'date', 
        'invoice_no'
    ];
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }
    public function driver()
    {
        return $this->belongsTo('App\Models\Driver', 'driver_id');
    }
}
