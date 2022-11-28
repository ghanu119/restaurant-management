<?php

namespace App\Models;

use App\Models\Helper\CommonFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class {MODEL_CLASS} extends Model
{
    use HasFactory, 
        CommonFunction;

    const IMAGE_PATH = 'app/public/files/{MODEL_TABLE_NAME}s';
    
    const DATATABLE_ORDER_COLUMN = [];

    protected $table = '{MODEL_TABLE_NAME}s';
    
    
    
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

}
