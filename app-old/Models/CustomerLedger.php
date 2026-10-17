<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerLedger extends Model
{
    use HasFactory;
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id')->select('id','name');
    }
    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice', 'invoice_id')->select('id','invoice_no');
    }
}
