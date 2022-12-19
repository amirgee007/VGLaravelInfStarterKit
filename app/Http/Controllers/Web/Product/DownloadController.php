<?php

namespace App\Http\Controllers\Web\Product;


use Vanguard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Vanguard\Repositories\Product\ProductRepository;
use Vanguard\Repositories\Product\EloquentProduct;
use Vanguard\Product;

class DownloadController extends Controller
{
    /**
     * @var EloquentProduct
     */
    private $products;

    /**
     * DownloadDataController constructor.
     * @param ProductRepository $products
     */
    public function __construct( ProductRepository $products)
    {
        $this->middleware('auth');
        $this->products = $products;
    }

    /**
     * @desc download image of product
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadImage(Request $request){
        $url = $request->get('url');
        $filename = substr($url, strrpos($url, '/') + 1);
        $tempImage = tempnam(sys_get_temp_dir(), $filename);
        copy($url, $tempImage);

        return response()->download($tempImage, $filename);
    }

    public function downloadExcel(Request $request){
        $data = $this->products->all()->toArray();

        return Excel::create('itsolutionstuff_example', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
        })->download('xlsx');
    }



}