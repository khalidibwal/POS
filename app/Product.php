<?php
//© 2020 Copyright: Tahu Coding
namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //demi keamanan kalian harusnya ubah ini ke fillable ya
    protected $guarded = [];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function customerTransaction()
    {
        return $this->belongsTo(CustomerTransaction::class, 'product_id', 'id');
    }
    
}
