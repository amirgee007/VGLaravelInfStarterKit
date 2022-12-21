<?php

namespace Vanguard\Http\Controllers\Web\Product;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Repositories\Product\ProductRepository;
use Vanguard\Repositories\Product\EloquentProduct;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class FetchDataController
 * @package Vanguard\Http\Controllers
 */
class FetchDataController extends Controller
{
    /**
     * @var EloquentProduct
     */
    private $products;
//    private $crawler;


    /**
     * FetchDataController constructor.
     * @param ProductRepository $products
     */
    public function __construct( ProductRepository $products)
    {
        $this->middleware('auth');
        $this->products = $products;
        $this->client = new \GuzzleHttp\Client();
//        $this->crawler = new Crawler();
    }

    /**
     * fetch data from product list
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function fetchData($page = 1)
    {
        try{
//            echo $page;
            $url = 'https://www.fruugo.us/home-garden/d-ws69316386?page='.$page;
//            echo $url;
            $response = $this->client->request('GET', $url)->getBody()->getContents();

            $crawler = new Crawler();
            $crawler->addHtmlContent($response);

            //there is just for test, so load only one page data, if there is need, load all page data by schedule
//            $total_page = $crawler->filter('.pagination a.d-none')->last()->text();
            $total_page = 1;
            if ($total_page >= $page) {
                //get list data by filter
                $data = $crawler->filter('.product-item')->each(function (Crawler $node, $i) {
                    //   echo '<br>'; echo $i;echo '<br>';
                    $title = $node->filter('.description')->text();
                    $price = $node->filter(('.price'))->text();
                    $imageSrc = $node->filter('.product-item-image-container img')->attr('data-src');
                    $itemUrl = $node->filter('a')->attr('href');
                    // not work???
                    // $image = $node->selectImage($title)->image();
                    // echo $imageUrl = $image->getUri();
                    $item = [
                        'thumbnail' => $imageSrc,
                        'original_title' => $title,
                        'price' => $price,
                        'url' => $itemUrl,
                    ];
                    $data[$i] = $item;
                    return $data;
                });
                // insert into table potential_products
//                print_r($data);
                if (count($data) > 0){
                    foreach ($data as $i =>$oneData){
                        print_r($oneData);
                        $product = $this->products->create($oneData[$i]);
                        $this->fetchDetailProduct($product->id, $product->url);
                    }
                }
                $nextPage = $page + 1;

                return  $this->fetchData($nextPage);
            }
        } catch (\Exception $e) {
        
        }
        
        // print_r($data);
            
    }


    /**
     * fetch detail data by id
     * @param $id
     * @param $url
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetchDetailProduct($id, $url){
        $domain = 'https://www.fruugo.us';

        $response = $this->client->request('GET', $domain . $url)->getBody()->getContents();
//            $product = $this->products->findByField('url', $url);
//            echo($product->id);
        $crawlerDetail = new Crawler();
        $crawlerDetail->addHtmlContent($response);

        $description = $crawlerDetail->filter('.js-product-description')->text();
        $extraImages = $crawlerDetail->filter('.js-gallery-thumb')->each(function (Crawler $node, $i) {
            return $data[$i] = $node->attr('data-image');
        });
        $data = [
            'extra_images' => implode(',', $extraImages),
            'original_description' => $description,
            'image' => count($extraImages) ? $extraImages[0] : '',
        ];
//            print_r($data);
        $this->products->update($id, $data);


    }


    /**product list page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $perPage = 20;
        $adminView = true;
        $domain = "https://www.fruugo.us";
        $products = $this->products->paginateProducts($perPage, $request->get('search'));
        return view('product.index', compact('products', 'adminView', 'domain'));
    }


}
