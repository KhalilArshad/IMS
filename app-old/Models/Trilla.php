<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trilla extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'driver_id',
        'reg_no',
        'modal',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
}
