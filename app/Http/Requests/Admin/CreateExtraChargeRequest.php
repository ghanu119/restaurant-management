<?php

namespace App\Http\Requests\Admin;

use App\Models\ExtraCharge;
use Illuminate\Foundation\Http\FormRequest;

class CreateExtraChargeRequest extends FormRequest
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
            $imgPath = ExtraCharge::IMAGE_PATH;

        }
        $rules = [
            'name' => 'required|max:200|unique:extra_charges,name' . $nameUnique,
            // 'color_image' => [
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
