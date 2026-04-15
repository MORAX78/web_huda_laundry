<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransOrder extends Model
{
    protected $table = 'trans_order';
    protected $fillable = [
        'id_customer', 'order_code', 'order_date',
        'order_end_date', 'order_status', 'order_pay',
        'order_change', 'total'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

    public function details()
    {
        return $this->hasMany(TransOrderDetail::class, 'id_order');
    }

    public function pickup()
    {
        return $this->hasOne(TransLaundryPickup::class, 'id_order');
    }
}
