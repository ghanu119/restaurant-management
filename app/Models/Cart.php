<?php

namespace App\Models;

use App\Models\Helper\CommonFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';
    
    public function scopeCurrentGuest ( $q ) {
        return $q->where('guest_id', session()->getId() );
    }

    public function getFlavourAttribute () {
        return Flavour::whereIn( 'id', explode( ',',$this->flavour_id ))->get();
    }

    public function getParcelTypeAttribute () {
        return ParcelType::where( 'id', $this->parcel_type_id)->first();
    }

    public function getToppingTypeAttribute () {
        if( $this->is_custom ){
            return 'custom';
        }
        return PackOfTopping::where( 'id', $this->pack_of_toppings_id)->first();
    }

    public function getToppingsAttribute () {
        return Color::whereIn( 'id', explode(',', $this->toppings_id ))->get();
    }

    public function getSubTotalAttribute () {
        $subTotal = 0;
        $subTotal += $this->parcel_type->price;
        $subTotal += $this->flavour->sum('price');
        if( $this->is_custom ){
            $subTotal += $this->toppings->sum('price');
        }else{
            $subTotal += $this->topping_type->price;

        }

        return $subTotal;
    }

    public function getTotalAttribute () {
        return $this->sub_total * $this->quantity;
    }
    
    
}
