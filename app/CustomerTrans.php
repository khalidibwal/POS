<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerTrans extends Model
{
    protected $table = 'customerTransaction';
    // protected $primaryKey = 'invoices_number';
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    protected $fillable = [
        'invoices_number', 'customer_id','product_id', 'pay', 'total',
        // Add other attributes as needed
    ];
}
