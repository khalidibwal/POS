<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormData extends Model
{
    protected $fillable = ['name', 'table_num','invoice_number'];
    protected $table = 'customer';
}
