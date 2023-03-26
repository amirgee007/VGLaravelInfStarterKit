<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Vanguard\Repositories\PotentialProducts\PotentialProductsRepository;
use Vanguard\Http\Controllers\Api\EmailController;
use Vanguard\Repositories\User\UserRepository;

class Notice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var PotentialProductsRepository
     */
    private $potentialProducts;
    private $userRepository;
    /**
     * @var string base dir
     */
    private $dir;
    /**
     * @var string Crawler url
     */
    private $url;
    /**
     * @var int uid
     */
    private $uid;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PotentialProductsRepository $potentialProducts, UserRepository $userRepository, $uid)
    {
        $this->potentialProducts = $potentialProducts;
        $this->userRepository = $userRepository;
        $this->dir = base_path();
        $this->uid = $uid;
        $this->url = 'https://www.fruugo.us';
    }

    /**
     * @desc exec list
     */
    public function index()
    {
        $content = $this->getUrlContent();
        $this->list($content);
    }

    /**
     * @desc  crawler list
     * @param $content
     */
    private function list($content){
        //begin crawler
        preg_match_all("/data-endpoint=\"(.*?)\"/", $content ,$match);

        if(empty($match[1])){
            return;
        }

        foreach ($match[1] as $childUrl) {
            $childUrl = $this->url.htmlspecialchars_decode($childUrl);
            //get product list
            $content = $this->getUrlContent($childUrl);
            $data = json_decode($content, true);
            if(!isset($data['productTiles'])){
                continue;
            }
            foreach ($data['productTiles'] as $val) {
                $productId = $this->potentialProducts->saveCrawlerData($val['imageUrl'], $this->url.$val['productPageLink']['href'], $val['title'], '', $val['price']);
                //get product info
                $childUrl = $this->url.$val['productPageLink']['href'];
                $content = $this->getUrlContent($childUrl);
                preg_match_all("/data-image=\"(.*?)\"/", $content, $infoMatch);
                preg_match("/<div class=\"a11y-text-width js-product-description Product__Description-text text-break-word\">(.*?)<\/div>/s", $content, $descriptionMatch);
                $imageUrl = $extraImages = $description = '';
                if(!empty($infoMatch[1])){
                    $imageUrl = $infoMatch[1][0];
                    array_shift($infoMatch[1]);
                    $extraImages = implode(',', $infoMatch[1]);
                }

                if(!empty($descriptionMatch[1])){
                    $description = $descriptionMatch[1];
                }

                $this->potentialProducts->updateCrawlerData($productId, $imageUrl, $extraImages, $description);
            }
        }
        //email notice
        $info = $this->userRepository->find($this->uid);
        (new EmailController())->send($info->email);
    }
    /**
     * @desc   get Cookie
     * @return string
     */
    private function getCookie(){
        $output = [];
        $content = '';
        file_put_contents($this->dir.'/time.txt', time());
        exec(app_path().'phantomjs '.base_path().'/resources/assets/js/getCookie.js',$output);
        if(!empty($output)) {
            $content = implode(";",$output);
        }
        file_put_contents($this->dir.'/cookie.txt', $content);
        return $content;
    }

    /**
     * @desc  do curl
     * @param $url
     * @param $cookie
     *
     * @return bool|string
     */
    private function doCurl($url, $cookie) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.119 Safari/537.36');
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }

    /**
     * @desc   get url content
     * @return bool|string
     */
    private function getUrlContent($url = ''){
        if(!file_exists($this->dir.'/time.txt')){
            $this->getCookie();
        }
        $last_time = file_get_contents($this->dir.'/time.txt');

        if((time() - $last_time) > (30*60)) {
            $cookie = $this->getCookie();
        }else{
            $cookie = file_get_contents($this->dir.'/cookie.txt');
        }

        $content = $this->doCurl(empty($url) ? $this->url : $url, $cookie);
        return $content;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->index();
    }
}
