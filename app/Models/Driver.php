<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $table = 'drivers';

    protected $fillable = ['name', 'phone_no', 'email', 'type'];

    /* ───────── Relationships ───────── */

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'driver_id');
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'driver_id');
    }

    public function expenses()
    {
        return $this->hasMany(VehicleExpense::class, 'driver_id');
    }

    public function customers()
    {
        return $this->hasMany(DriverCustomer::class, 'driver_id');
    }
}
