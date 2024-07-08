<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerTransactionSummary extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'date',
        'today_bill',
        'today_remaining',
        'old_remaining',
        'old_received',
        'net_remaining',
        'description',
    ];

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }
}
