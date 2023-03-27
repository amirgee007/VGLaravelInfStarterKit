<?php

namespace Vanguard\Http\Controllers\Web;

use Auth;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\DomCrawler\Crawler;
use Vanguard\Exports\PotentialProductsExport;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Jobs\ProcessCrawlJob;
use Vanguard\Repositories\Crawl\PotentialProductRepository;
use Vanguard\Repositories\User\UserRepository;

/**
 * Class CrawlController
 * @package Vanguard\Http\Controllers
 */
class CrawlController extends Controller
{

    private $httpClient = null;
    private $potentialProduct;
    private $userRepository;

    /**
     * CrawlController constructor.
     * @param PotentialProductRepository $potentialProduct
     * @param UserRepository $userRepository
     */
    public function __construct(PotentialProductRepository $potentialProduct, UserRepository $userRepository)
    {
        $this->middleware('auth');
        $this->middleware('permission:crawl.manage');
        $this->httpClient = new HttpClient([
            'timeout' => 0
        ]);
        $this->potentialProduct = $potentialProduct;
        $this->userRepository = $userRepository;
    }

    /**
     * Displays the page with crawl data for admin role.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $perPage = 5;
        $adminView = true;

        $products = $this->potentialProduct->paginatePotentialProducts($perPage, $request->get('search'));

        return view('crawl.index', compact('products', 'adminView'));
    }

    /**
     * Download products.
     *
     * @param Request $request
     * @return string|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadProducts(Request $request)
    {
        $products = $this->potentialProduct->lists($request->get('search'), ['id','rank','thumbnail','url','original_title','price','english_title','chinese_title','image','created_at','updated_at']);
        if (!$products) {
            return 'Products is empty!';
        }
        $title = [
            'id'             => trans('app.id'),
            'rank'           => trans('app.rank'),
            'thumbnail'      => trans('app.thumbnail'),
            'url'            => trans('app.url'),
            'original_title' => trans('app.original_title'),
            'price'          => trans('app.price'),
            'english_title'  => trans('app.english_title'),
            'chinese_title'  => trans('app.chinese_title'),
            'image'          => trans('app.image'),
            'created_at'     => trans('app.created_at'),
            'updated_at'     => trans('app.updated_at')
        ];

        array_unshift($products, $title);
        $export = new PotentialProductsExport($products);

        return Excel::download($export, 'potential-products-' . date('ymdHi') . '.xlsx')->deleteFileAfterSend(true);
    }

    /**
     * Download the images of product.
     *
     * @param Request $request
     * @return bool|$this
     */
    public function downloadImages(Request $request)
    {
        $product_id = $request->input('product_id');
        if (!$product_id) {
            return false;
        }
        $product = $this->potentialProduct->find($product_id);
        if (!$product) {
            return false;
        }

        $images = explode(',', $product['extra_images']);
        $zip_name = 'product-' . $product_id . '-images' . date('ymdHi') . '.zip';
        $zip_file = tempnam(public_path(), '');
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        foreach ($images as $key => $image_url) {
            $content = file_get_contents($image_url);
            $ext = pathinfo($image_url);
            $zip->addFromString("picture-" . ($key + 1) . ($ext['extension'] ?? '.png'), $content);
        }
        $zip->close();
        return response()->download($zip_file, $zip_name)->deleteFileAfterSend(true);
    }

    public function scrape()
    {
        try {
            dispatch((new ProcessCrawlJob(Auth::user()->id, $this->userRepository, $this->potentialProduct))->onConnection('redis')->onQueue('allegro_crawl'));
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            exit;
        }

        echo "The crawler is already in the queue, please refresh the list page later" . PHP_EOL;
        exit;
    }

    public function getProducts()
    {
        // begin scraping data
        $url = 'https://allegro.pl/';

        $response = $this->httpClient->get($url, [
            'headers' => [
//                'cookie'                    => 'wdctx=v4.zPDcLKBpYRNJMKIIToklEzAElkXFKfl7L9vx6BFY3q6tADuC9w0rStx69XuQHj4UKsG02BmWdCXOG8iFwQQcetxpZfxYz_wGFhfakJF2Xv0_4luNLXEa81GiA863FooWsGJcPnNAooYo525CBF0i57xZPYzn0Gy2rF32kpBGar3FK_P4cEV15HBrsZqub83xBfDorzJ-cLpvnhPZD2dNL5DaxQVoEvlU2lJVbWsmp20Jrz76Do56PxBl2xI; _cmuid=a7b4ce36-354c-4f6a-b7a0-95ed9dd0be0f; datadome=4ipiTO~k9Dk2X~dQJTpH-41Vrc_bG2DpSkF2OQ2XkH0Iwr4x9cOsHEagRbWMvYjHzKn5opp7GNWsadAbxyaca7Buw2KfOam~K4_8ViAd1MKYmEojMLiuOSJTgVEphPoT; _gcl_au=1.1.496113492.1679134120; gdpr_permission_given=1; _ga_G64531DSC4=GS1.1.1679320273.6.1.1679320284.49.0.0; _ga=GA1.2.1730635568.1679134183; __gfp_64b=rseBHh6BcMdC2xFYXf3R049LOn5oYUyknHVNqaENaiL.s7|1679134920; _gid=GA1.2.611106898.1679282979',
//                'user-agent'                => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/110.0',
//                'Accept'                    => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
//                'Accept-Encoding'           => 'gzip, deflate, br',
//                'Accept-Language'           => 'zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
//                'Connection'                => 'keep-alive',
//                'Host'                      => 'allegro.pl',
//                'Sec-Fetch-Dest'            => 'document',
//                'Sec-Fetch-Mode'            => 'navigate',
//                'Sec-Fetch-Site'            => 'none',
//                'Sec-Fetch-User'            => '?1',
//                'Upgrade-Insecure-Requests' => '1',

                'Host' => 'allegro.pl',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/110.0',
                'Accept' => 'application/vnd.opbox-web.subtree+json',
                'Accept-Language' => 'zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Referer' => 'https://allegro.pl/',
                'x-box-id' => 'tPpiB1ODT32pC6WJ8U-hyw==02KUqRFmQQWCa9sHD4l4_g==rutadd_GSIWwbk-n4JpSpw==',
                'x-view-id' => '0d17d28b-c020-4cee-8256-52f1fe422b27',
                'Connection' => 'keep-alive',
                'Cookie' => 'wdctx=v4.GvQHfUBUcawamVrf7-aAb8bcMfXkWgNdt6Jw7gASCIdYSu5azGqzSoqTTWlz5gkRSVAN3ST1l1pnWFisJfNFlgeFl3XFAjVEwRpPXilL3BbUPdJcIvWzgDR29PwC5BgmmuG4j7pERwH8hdrH85ZdVVEPqrOchrHk_V0bpf1etWw0cbkKVSKd-Bcqppf5HTgV5RIUvOfqlJ9rwMfuHj1tydwCTwdIBgbMuZsz1XcZJYzl36HfBveVpiRr5B-X; _cmuid=a7b4ce36-354c-4f6a-b7a0-95ed9dd0be0f; datadome=2-RpvTr~97GUTgn1MD_RR_tdYw9aVRCrtVsUgLfT853wxZFp7kk6iytV3kzB3F~vXtseDymjXYTVXVfTtb7uI_efwSNZmnxujzH20WYhcDxGwN49_gBDJ_eWMwwACLkU; _gcl_au=1.1.496113492.1679134120; gdpr_permission_given=1; _ga_G64531DSC4=GS1.1.1679367387.8.1.1679371145.54.0.0; _ga=GA1.2.1730635568.1679134183; __gfp_64b=rseBHh6BcMdC2xFYXf3R049LOn5oYUyknHVNqaENaiL.s7|1679134920; _gid=GA1.2.611106898.1679282979; _gat_UA-2827377-1=1',
                'Sec-Fetch-Dest' => 'empty',
                'Sec-Fetch-Mode' => 'cors',
                'Sec-Fetch-Site' => 'same-origin',
                'TE' => 'trailers',
            ]
        ])->getBody()->getContents();

        $response = json_decode($response, true);
        $response = $response['htmlString'] ?? '';

        $crawler = new Crawler();
        $crawler->addHtmlContent($response);

        $products = $crawler->filterXPath('//div[contains(@id, "carousel-reco-carousel")]//div[@class="mpof_ki mr3m_1 mjyo_6x gel0f _e8529_NFJ-e g1s2l guv1z g1pyo g167r g12dg"]')
            ->each(function (Crawler $node) {
                return [
                    'thumbnail' => $node->filterXPath('//div[@class="mpof_z0 mp7g_f6 mj7u_0 mq1m_0 mnjl_0 mqm6_0 m7er_k4"]/img')->attr('data-src'),
                    'price'     => $node->filterXPath('//div[@class="mli8_k4 msa3_z4 mqu1_1 mp0t_ji m9qz_yo mgmw_qw mgn2_27 mgn2_30_s"]')->text(),
                    'title'     => $node->filterXPath('//a[@class="mp0t_0a mgmw_wo mj9z_5r mli8_k4 mqen_m6 l1fas l1igl mgn2_13 mqu1_16 meqh_en mpof_92 msub_k4 _e8529_NrY0A"]')->text(),
                    'info_url'  => $node->filterXPath('//a[@class="mp0t_0a mgmw_wo mj9z_5r mli8_k4 mqen_m6 l1fas l1igl mgn2_13 mqu1_16 meqh_en mpof_92 msub_k4 _e8529_NrY0A"]')->attr('href'),
                    'item_id'   => $node->filterXPath('//a[@class="mp0t_0a mgmw_wo mj9z_5r mli8_k4 mqen_m6 l1fas l1igl mgn2_13 mqu1_16 meqh_en mpof_92 msub_k4 _e8529_NrY0A"]')->attr('data-analytics-click-custom-item-id'),
                ];
            });

        // put it into queue to crawl and increase the monitoring platform to monitor the progress of crawling in real time
        foreach ($products as $product) {
            $this->_getProductInfo($product);
        }
    }

    private function _getProductInfo(array $product)
    {
        $url = $product['info_url'];

        $response = $this->httpClient->get($url, [
            'headers' => [
                'cookie'                    => 'wdctx=v4.zPDcLKBpYRNJMKIIToklEzAElkXFKfl7L9vx6BFY3q6tADuC9w0rStx69XuQHj4UKsG02BmWdCXOG8iFwQQcetxpZfxYz_wGFhfakJF2Xv0_4luNLXEa81GiA863FooWsGJcPnNAooYo525CBF0i57xZPYzn0Gy2rF32kpBGar3FK_P4cEV15HBrsZqub83xBfDorzJ-cLpvnhPZD2dNL5DaxQVoEvlU2lJVbWsmp20Jrz76Do56PxBl2xI; _cmuid=a7b4ce36-354c-4f6a-b7a0-95ed9dd0be0f; datadome=4ipiTO~k9Dk2X~dQJTpH-41Vrc_bG2DpSkF2OQ2XkH0Iwr4x9cOsHEagRbWMvYjHzKn5opp7GNWsadAbxyaca7Buw2KfOam~K4_8ViAd1MKYmEojMLiuOSJTgVEphPoT; _gcl_au=1.1.496113492.1679134120; gdpr_permission_given=1; _ga_G64531DSC4=GS1.1.1679320273.6.1.1679320284.49.0.0; _ga=GA1.2.1730635568.1679134183; __gfp_64b=rseBHh6BcMdC2xFYXf3R049LOn5oYUyknHVNqaENaiL.s7|1679134920; _gid=GA1.2.611106898.1679282979',
                'user-agent'                => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/110.0',
                'Accept'                    => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
                'Accept-Encoding'           => 'gzip, deflate, br',
                'Accept-Language'           => 'zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
                'Connection'                => 'keep-alive',
                'Host'                      => 'allegro.pl',
                'Referer'                   => 'https://allegro.pl/',
                'Sec-Fetch-Dest'            => 'document',
                'Sec-Fetch-Mode'            => 'navigate',
                'Sec-Fetch-Site'            => 'same-origin',
                'Sec-Fetch-User'            => '?1',
                'Upgrade-Insecure-Requests' => '1',
            ]
        ])->getBody()->getContents();

        $crawler = new Crawler();
        $crawler->addHtmlContent($response);

        try {
            $title = $product['title'];
//            $title = $crawler->filterXPath('//div[contains(@data-box-name, "showoffer.productHeader")]//h4')->text();
            $description = $crawler->filterXPath('//div[@data-box-name="Description card"]')->html();
            $images = $crawler->filterXPath('//div[contains(@data-box-name, "allegro.showoffer.gallery")]//div[@class="_e5e62_U0UTX"]')
                ->each(function (Crawler $node) {
                    return $node->filterXPath('//img')->attr('src');
                });

            $potential_product = [
                'rank'                 => $crawler->filterXPath('//div[contains(@data-box-name, "showoffer.productHeader")]//div[@class="mpof_ki mwdn_1"]')->attr('data-analytics-view-custom-rating-value'),
                'thumbnail'            => $product['thumbnail'],
                'url'                  => $product['info_url'],
                'original_product_id'  => $product['item_id'],
                'original_title'       => $title,
                'original_description' => $description,
                'price'                => $product['price'],
                'english_title'        => $this->translate($title, 'auto', 'en'),
                'english_description'  => $this->translate($description, 'auto', 'en'),
                'chinese_title'        => $this->translate($title, 'auto', 'zh'),
                'chinese_description'  => $this->translate($description, 'auto', 'zh'),
                'image'                => $images[0] ?? '',
                'extra_images'         => implode(',', $images),
            ];

            // Remove duplicate crawled products
            // save product into database
            $this->potentialProduct->updateOrCreate(['original_product_id' => $product['item_id']], $potential_product);
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            exit;
        }
    }

    public function translate($text, $from = 'en', $to = 'zh')
    {
        // todo translate into English and Chinese
        // Access translation interface, such as: Baidu translation api
        return '';
    }
}
