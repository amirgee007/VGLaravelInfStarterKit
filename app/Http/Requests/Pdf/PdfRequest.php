<?php
/**
 * @desc
 * @author     WenMing<st-m1ng@163.com>
 * @date       2023-03-27 5:08
 */
namespace Vanguard\Http\Requests\Pdf;

use Vanguard\Http\Requests\Request;

class PdfRequest extends Request
{
    /**
     * @desc   pdf rules
     * @return string[]
     */
    public function rules()
    {
        $rules = [
            'pdf_file' => 'required',
            'product_id' => 'required|digits_between:10,50',
            'quantity' => 'required',
            'stock_location' => 'required|size:8|alpha_num',
            'ean_number' => 'required|digits:14',
        ];

        return $rules;
    }
}