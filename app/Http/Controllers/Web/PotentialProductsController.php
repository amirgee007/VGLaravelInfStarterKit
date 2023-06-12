<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Repositories\PotentialProduct\EloquentPotentialProduct;

class PotentialProductsController extends Controller
{
    /**
     * @var EloquentPotentialProduct
     */
    public $potentialProducts;

    public function __construct(EloquentPotentialProduct $potentialProducts)
    {
        $this->middleware('auth');
        $this->potentialProducts = $potentialProducts;
    }

    /**
     * Displays the page with products for all system users.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $potentialProducts = $this->potentialProducts->paginate( $perPage = 10, $request->get('search') );
        return view('potentialProducts.index', compact('potentialProducts'));
    }
}
