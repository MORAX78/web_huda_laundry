<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    protected $table = 'customer';
    protected $fillable = ['customer_name', 'phone', 'address'];

    public function orders()
    {
        return $this->hasMany(TransOrder::class, 'id_customer');
    }
}
