<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierLedger extends Model
{
    use HasFactory;
    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier', 'supplier_id')->select('id','name');
    }
    public function purchaseOrder()
    {
        return $this->belongsTo('App\Models\PurchaseOrder', 'purchase_order_id')->select('id','po_no');
    }
}
