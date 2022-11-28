<?php

namespace App\Models;

use App\Models\Helper\CommonFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    public function customer () {
        return $this->belongsTo( User::class, 'user_id', 'id');
    }

    public function table () {
        return $this->belongsTo( Table::class, 'table_id', 'id');
    }

    public function orderItems () {
        return $this->hasMany( OrderItem::class, 'order_id', 'id');
    }

    public function scopeNotCompleted ( $q ) {
        return $q->where( 'status', '!=', 3);
    }


    /**
     * @name allColumnFilter
     *
     * To get only active brand
     */
    public function scopeAllColumnFilter ( $q, $value ){
        return $q->where(function ( $where ) use ( $value ) {
            $where->orWhereHas('customer', function( $customerQry ) use ( $value ){
                $customerQry->where('name', 'LIKE', '%'. $value . '%');
            });
            $where->orWhere('id', 'LIKE', '%'. $value . '%');
            $where->orWhere('day_wise_id', 'LIKE', '%'. $value . '%');
        });
    }
}
