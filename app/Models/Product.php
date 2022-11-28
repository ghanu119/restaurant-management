<?php

namespace App\Models;

use App\Models\Helper\CommonFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory,
        CommonFunction;

    const IMAGE_PATH = 'app/public/files/products';

    protected $table = 'products';


    public function category (){
        return $this->belongsTo( Category::class, 'category_id', 'id');
    }
    public function chargesList (){
        return $this->hasMany( ProductExtraCharges::class, 'product_id', 'id');
    }

    public function extraCharges () {
        return $this->belongsToMany( ExtraCharge::class, 'product_extra_charges_pivot', 'product_id','extra_charges_id' );
    }


    /**
     * @name allColumnFilter
     *
     * To get only active brand
     */
    public function scopeAllColumnFilter ( $q, $value ){
        return $q->where(function ( $where ) use ( $value ) {
            $where->orWhere('name', 'LIKE', '%'. $value . '%');
            $where->orWhere('description', 'LIKE', '%'. $value . '%');
        });
    }

    /**
     * @name selected_charges
     */
    public function getSelectedChargesAttribute () {
        if( old('charges') ){
            return old('charges');
        }
        $charges = [];
        if( $this->chargesList->count() ){
            foreach( $this->chargesList as $chargeData ){
                $charges[] = [
                    'id' => $chargeData->extra_charges_id,
                    'name' => $chargeData->extraCharge->name,
                    'price' => $chargeData->price,
                    'original_price' => $chargeData->extraCharge->price,
                ];
            }
        }
        return $charges;
    }
}
