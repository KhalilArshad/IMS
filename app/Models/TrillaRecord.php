<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrillaRecord extends Model
{
    use HasFactory;
    protected $fillable = [
        'trilla_id',
        'date',
        'total_trip_amount',
        'total_diesel',
        'total_maintenance',
        'total_d_trip',
        'other_expense'
    ];

    public function trillaRecordChild()
    {
        return $this->hasMany(TrillaRecordChild::class,'trilla_record_id','id');
    }
    public function trilla()
    {
        return $this->belongsTo(Trilla::class,'trilla_id','id');
    }
}
