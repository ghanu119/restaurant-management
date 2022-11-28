<?php

namespace App\Models;

use App\Models\Helper\CommonFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{

    public function order () {
        return $this->belongsTo( Order::class, 'order_id', 'id');
    }

    public function orderProducts () {
        return $this->belongsTo( Product::class, 'product_id', 'id');
    }

    public function productCharges () {
        return $this->hasMany( OrderProductExtraCharge::class, 'order_item_id', 'id');
    }
}
