<?php

namespace Vanguard\Http\Controllers\Web\Product;

use Illuminate\Support\Facades\Storage;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Repositories\Product\ProductRepository;
use Vanguard\Repositories\Product\EloquentProduct;
use Illuminate\Http\Request;
use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Worksheet_Drawing;


/**
 * Class FetchDataController
 * @package Vanguard\Http\Controllers
 */
class DownloadDataController extends Controller
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

    /**
     * get all product data
     * @return array
     */
    protected function getData(){
        $data = $this->products->all()->toArray();
        return $data;
    }

    /**
     * @desc download image to storage path and return the path
     * @param string $url
     * @return string
     */
    protected function uploadFromUrl(string $url): string
    {
        $contents = file_get_contents($url);
        $storage_disk = 'public/';
        $filename = pathinfo($url)['basename'];

        Storage::disk($storage_disk)->put($filename, $contents);

        return Storage::disk($storage_disk)->path($filename);
    }

    /** download execl
     * @param Request $request
     */
    public function downloadExcel(Request $request)

    {

        Excel::create('Products Data',function ($excel) {

            $excel->sheet('Products Data',function ($sheet) {

                $sheet->setStyle([
                    'font' => [
                        'name' => 'Calibri',
//                        'size' => 15,
                        'bold' => false,
                    ]
                ]);

                $head= [__('app.id'),  __('app.thumbnail'),__('app.rank'), __('app.url'), __('app.original_title'), __('app.original_description'), __('app.image')
                    , __('app.price') , __('app.english_title'), __('app.english_description'),  __('app.chinese_title'),  __('app.chinese_description')];

                //init column
                $title_array = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];
                $rows = collect([$head]);
                $sheet->rows($rows);
                $arr = [];
                $autoNum = 0;
                $domain = 'https://www.fruugo.us';

                foreach ($this->getData() as $item)

                {
                    $arr[$autoNum]['id'] = $item['id'];
                    $arr[$autoNum]['thumbnail'] = $item['thumbnail'];
                    $arr[$autoNum]['rank'] = $item['rank'];
                    $arr[$autoNum]['url'] = $domain.$item['url'];
                    $arr[$autoNum]['original_title'] = $item['original_title'];
                    $arr[$autoNum]['original_description'] = $item['original_description'];
                    $arr[$autoNum]['price'] = $item['price'];
                    $arr[$autoNum]['english_title'] = $item['english_title'];
                    $arr[$autoNum]['english_description'] = $item['english_description'];
                    $arr[$autoNum]['chinese_title'] = $item['chinese_title'];
                    $arr[$autoNum]['chinese_description'] = $item['chinese_description'];
                    $autoNum++;
                }

                $sheet->rows($arr);//insert excel

                // deal with product image

                foreach ($arr as $k=> &$item)

                {
                    $obj= new PHPExcel_Worksheet_Drawing();// use phpExcel
                    $obj->setPath($this->uploadFromUrl($item['thumbnail']));
                    $sp= $title_array[0 + 3];
                    $obj->setCoordinates('B'.($k + 2));//set location of image
                    $sheet->setHeight($k + 2, 65);//set height
                    $sheet->setWidth(array($sp=> 11));//set width
                    $obj->setHeight(80);
                    $obj->setOffsetX(1);
                    $obj->setRotation(1);
                    $obj->setWorksheet($sheet);
                }

            });

        })->export('xls');


    }

}
