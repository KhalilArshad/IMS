<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrillaRecordChild extends Model
{
    use HasFactory;
    Protected $fillable = [
        'trilla_record_id',
        'from_location',
        'to_location',
        'date',
        'trip_amount',
        'diesel',
        'maintenance',
        'd_trip',
        'cash_received',
        'bill_no',
    ];

    public function trillaRecord()
{
    return $this->belongsTo(TrillaRecord::class, 'trilla_record_id');
}
}
