<?php
/**
 * @desc
 * @author     WenMing<st-m1ng@163.com>
 * @date       2023-03-20 16:24
 */
namespace Vanguard\Http\Controllers\Web;

use Vanguard\Repositories\PotentialProducts\PotentialProductsRepository;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Vanguard\Exports\GoodsExport;
use Maatwebsite\Excel\Facades\Excel;

class PotentialProductsController extends Controller
{
    /**
     * @var PotentialProductsRepository
     */
    private $potentialProducts;
    //potential_products
    public function __construct(PotentialProductsRepository $potentialProducts)
    {
        $this->middleware('auth');
        $this->middleware('permission:users.manage');
        $this->potentialProducts = $potentialProducts;
    }

    public function index(Request $request)
    {
        $perPage = 20;
        $adminView = true;

        $products = $this->potentialProducts->paginatePotentialProducts($perPage, $request->get('search'));
        $url = '/crawler';
        $uid = auth()->id();
        return view('potential_products.index', compact('products', 'adminView', 'url', 'uid'));
    }

    /**
     * @desc  export excel
     * @param Request $request
     */
    public function excel(Request $request){
        $fields = 'id,thumbnail,url,original_title,price,original_description,created_at';
        $data = $this->potentialProducts->all($request->input('search'), $fields);
        $result = array_merge([[
            'ID',
            'Thumbnail',
            'Url',
            'Original Title',
            'Price',
            'Original Description',
            'Created At'
        ]], $data);
        $name = 'PotentialProducts.xlsx';
        return Excel::download(new GoodsExport($result), $name);
    }
}