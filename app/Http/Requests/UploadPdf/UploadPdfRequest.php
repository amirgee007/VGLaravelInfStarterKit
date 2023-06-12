<?php

namespace Vanguard\Http\Requests\UploadPdf;

use Vanguard\Http\Requests\Request;

class UploadPdfRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => 'required|min:10|integer',
            'stock_location' => 'required|alpha_num|min:8|max:8',
            'ean_number' => 'required|digits:14',
        ];
    }
}
