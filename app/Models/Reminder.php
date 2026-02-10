<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'invoice_id',
        'reminder_date',
        'message',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class)->select('id', 'name');
    }
    public function invoice()
    {
        return $this->belongsTo(Invoice::class)->select('id', 'invoice_no', 'date');
    }
}
