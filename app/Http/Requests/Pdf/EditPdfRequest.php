<?php

namespace Vanguard\Http\Requests\Pdf;

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
        $rules = [
            'pdf_file'       => '',
            'product_id'     => 'required|digits_between:10,50',
            'quantity'       => 'required',
            'stock_location' => 'required|alpha_num|size:8',
            'ean_number'     => 'required|digits:14',
        ];

        return $rules;
    }
}
