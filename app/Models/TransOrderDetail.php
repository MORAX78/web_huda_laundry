<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransOrderDetail extends Model
{
    protected $table = 'trans_order_detail';
    protected $fillable = ['id_order', 'id_service', 'qty', 'subtotal', 'notes'];

    public function service()
    {
        return $this->belongsTo(TypeOfService::class, 'id_service');
    }
}
