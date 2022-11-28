<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $nameUnique = '';
        $imgPath = null;
        if( $this->id ){
            $nameUnique = ','.$this->id.',id';
            $imgPath = Product::IMAGE_PATH;

        }
        $rules = [
            'name' => 'required|max:200|unique:products,name' . $nameUnique,
            // 'flavour_image' => [
            //     'required',
            //     new CheckMediaExist( $imgPath )
            // ],
            'price' => 'nullable|numeric|min:0|max:999999999',
            'description' => 'required|max:255',
            'status' => 'required|in:n,y',
        ];
        return $rules;
    }

}
