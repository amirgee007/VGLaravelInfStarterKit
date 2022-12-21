<?php

namespace Vanguard\Http\Requests\Product;

use Vanguard\Http\Requests\Request;

class EditPdfRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id'        => 'required|digits_between:11, 20',
            'stock_location'    => 'required|size:8|alpha_num',
            'quantity'          => 'required',
            'ean_number'        => 'required|digits:14'
        ];
    }

    public function messages()
    {
        return [
//            'product_id.numeric' => 'Maximum number of records per page is 100.'
        ];
    }
}
