<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 
        'status', 
        'total_bill', 
        'paid_amount', 
        'remaining', 
        'date', 
        'invoice_no'
    ];
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }
}
