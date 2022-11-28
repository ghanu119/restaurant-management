<?php

namespace App\Models;

use App\Models\Helper\CommonFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProductExtraCharge extends Model
{

    public function order_item () {
        return $this->belongsTo( OrderItem::class, 'order_item_id', 'id');
    }

    public function extraCharge () {
        return $this->belongsTo( ProductExtraCharges::class, 'extra_charge_id', 'id');
    }
}
