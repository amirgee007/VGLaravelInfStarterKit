<?php

namespace Vanguard\Http\Controllers\Web\Logic;

use Illuminate\Support\Facades\Storage;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Repositories\Product\ProductRepository;
use Vanguard\Repositories\Product\EloquentProduct;
use Illuminate\Http\Request;
use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Worksheet_Drawing;


/**
 * Class TestController
 * @package Vanguard\Http\Controllers
 */
class TestController extends Controller
{


    /**
     * DownloadDataController constructor.
     * @param ProductRepository $products
     */
    public function __construct( ProductRepository $products)
    {
        $this->middleware('auth');
        $this->products = $products;
    }

    public function index(){
        return view('logic.index');
    }

    public function checkMiss(Request $request){
        $checkNumber = $request->get('checkNumber');
        $checkNumberArr = explode(',', $checkNumber);
        $lastCharacter_one = substr($checkNumberArr[0], -1);
        $base = rtrim($checkNumberArr[0], $lastCharacter_one);
        if (isset($checkNumberArr[1])){
            $lastCharacter = substr($checkNumberArr[1], -1);
        }else{
            $lastCharacter = $lastCharacter_one;
        }
        for($i = 'A'; $i <= $lastCharacter; $i++){

            if ($lastCharacter == $i || $lastCharacter_one == $i){
                continue;
            }else{
                echo $base.$i;echo '<br>';
            }
        }
        exit;

    }

    public function checkMatch(Request $request){
        $checkMatch = $request->get('checkMatch');

        $matchLeft = ['{' => '{', '(' => '(', '[' => '[','}' => '1', ')'  => '2', ']' => '3' ];
        $matchRight = ['}' => '{', ')'  => '(', ']' => '[', '{' => '4', '(' => '5', '[' => '6'];
//        $checkMatch = '([{}]';

        $length = strlen($checkMatch);


        $mathArr = str_split($checkMatch);
        if (array_diff(array_unique($mathArr), array_keys($matchLeft)) ){
            echo 'please input invalid bracket';exit;
        }
        if ($length > 105){
            echo 'max length is 104';exit;
        }
        if ($length % 2 !== 0){
            echo 'false';exit;
        }
        $flag = 'true';
        $ignoreLocation = [];

        for($i = 0; $i < $length; $i++){
            // echo $i;
            //if is the ignore location just continue;
            if (in_array($i, $ignoreLocation)){
                continue;
            }
            //espetial case :[()](){}
//            echo $i.$matchLeft[$mathArr[$i]] .'==='.$matchRight[$mathArr[($length -1 - $i)]] .'&&'. $matchLeft[$mathArr[$i]] .'==='.$matchRight[$mathArr[$i + 1]];
            // check rule one and two
            if ($matchLeft[$mathArr[$i]] !== $matchRight[$mathArr[($length -1 - $i)]] && $matchLeft[$mathArr[$i]] !== $matchRight[$mathArr[$i + 1]]){
                $flag = 'false';break;
            }else{
                
                if ($matchLeft[$mathArr[$i]] === $matchRight[$mathArr[$i + 1]]){ //match by closet
                    $ignoreLocation[] = $i + 1;
                }

                if ($matchLeft[$mathArr[$i]] === $matchRight[$mathArr[($length -1 - $i)]]){ //match by correct order
                    $ignoreLocation[] = ($length -1 - $i);
                }
            }
        }

        echo $flag;exit;

    }

}
