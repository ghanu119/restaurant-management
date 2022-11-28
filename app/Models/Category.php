<?php

namespace App\Models;

use App\Models\Helper\CommonFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory,
        CommonFunction;

    const IMAGE_PATH = 'app/public/files/category';

    protected $table = 'category';

    public function products (){
        return $this->hasMany( Product::class, 'category_id', 'id');
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

}
