<?php

namespace App\Models;

use App\Models\Helper\CommonFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductExtraCharges extends Model
{
    use HasFactory,
        CommonFunction;

    protected $table = 'product_extra_charges_pivot';
    public $timestamps = false;

    public function product (){
        return $this->belongsTo( Product::class, 'product_id', 'id');
    }
    public function extraCharge (){
        return $this->belongsTo( ExtraCharge::class, 'extra_charges_id', 'id');
    }

}
