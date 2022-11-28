<?php

namespace App\Models;

use App\Models\Helper\CommonFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory,
        CommonFunction;

    const IMAGE_PATH = 'app/public/files/table';

    const DATATABLE_ORDER_COLUMN = [];

    protected $table = 'tables';

    public function currentOrder (){
        return $this->hasOne( Order::class, 'table_id', 'id')->latest();
    }

    /**
     * @name allColumnFilter
     *
     * To get only active brand
     */
    public function scopeAllColumnFilter ( $q, $value ){
        return $q->where(function ( $where ) use ( $value ) {
            $where->orWhere('name', 'LIKE', '%'. $value . '%');
        });
    }


    public function pack_of_toppings () {
        return $this->belongsToMany( PackOfTopping::class, 'pack_of_topping_pivot', 'topping_id', 'pack_of_toppings_id' );
    }
}
