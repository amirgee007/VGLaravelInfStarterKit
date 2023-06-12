<?php

namespace Vanguard\Http\Controllers\Api;

use Carbon\Carbon;
use QL\QueryList;
use Vanguard\PotentialProduct;
use Vanguard\Repositories\PotentialProduct\PotentialProductRepository;

class PotentialProductsController extends ApiController
{
    public $potentialProducts;
    public $queryList = [];

    public function __construct(PotentialProductRepository $potentialProducts)
    {
//        $this->middleware('auth');
        $this->potentialProducts = $potentialProducts;

        $this->queryList['rule'] = config( 'queryListRules.potentialProductsRule' );
        $this->queryList['subRule'] = config( 'queryListRules.potentialProductsSubRule' );
        $this->queryList['range'] = config( 'queryListRules.potentialProductsRange' );
        $this->queryList['subRange'] = config( 'queryListRules.potentialProductsSubRange' );
        $this->queryList['url'] = config( 'queryListRules.targetUrl' );
    }


    public function syncProduct(){
        try{
            //crawl from target url
            $data = $this->getQueryList( $this->queryList['url'], $this->queryList['rule'], $this->queryList['range'] );
            if( !empty( $data ) ){
                foreach( $data as $key => $value ){
                    if( empty( $value['thumbnail'] ) ) {
                        unset($data[$key]);
                        continue;
                    }
                    //to prevent 429,too many requests
                    sleep(1);
                    $result = $this->getQueryList($value['url'], $this->queryList['subRule'], $this->queryList['subRange']);
                    $data[$key]['rank'] = $key+1;
                    $data[$key]['english_title'] = $result[0]['english_title'];
                    $data[$key]['english_description'] = $result[0]['english_description'];
                    $data[$key]['created_at'] = Carbon::now();
                    $data[$key]['updated_at'] = Carbon::now();
                }
            }
            $data = array_values( $data );
            PotentialProduct::insert($data);
            return $this->respondWithArray(['sync complete']);
        } catch( \Exception $exception){
            \Log::error( 'queryList error:'.json_encode( $exception->getMessage() ) );
            return $this->respondWithError(['unknown error']);
        }
    }
    public function getQueryList( $url, $rules, $range = '' ){
        $data = QueryList::get($url, null, [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36',
                'Accept-Encoding' => 'gzip, deflate, br',
            ]
        ])->rules($rules);
        if( !empty( $range ) ){
            $data = $data->range( $range );
        }
        return $data->queryData();
    }
}
