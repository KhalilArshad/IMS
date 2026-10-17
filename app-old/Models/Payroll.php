<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'salary',
        'overtime',
        'advance',
        'paid_in_advance',
        'remaining',
        'add_in_remaining',
        'total_salary_to_be_paid',
        'description',
        'month',
    ];

  public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'employee_id');
    }
}
