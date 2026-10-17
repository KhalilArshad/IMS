<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 
        'email', 
        'cnic_no', 
        'phone_no', 
        'designation', 
        'advance', 
        'salary', 
        'date_of_joining', 
        'address', 
    ];
}
