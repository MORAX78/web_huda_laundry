<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransOrder extends Model
{
    use SoftDeletes;
    protected $table = 'trans_order';
    protected $fillable = [
        'id_customer', 'is_member', 'order_code', 'order_date',
        'order_end_date', 'order_status', 'order_pay',
        'order_change', 'total', 'tax', 'discount', 'voucher_code'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer')->withTrashed();
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
