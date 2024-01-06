<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'Customer';
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function transactions()
    {
        return $this->hasMany(CustomerTrans::class);
    }
}
