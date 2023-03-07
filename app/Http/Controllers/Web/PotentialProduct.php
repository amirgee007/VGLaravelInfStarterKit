<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use Vanguard\Jobs\CollectJob;
use Vanguard\Jobs\PotentialFetchJob;
use Vanguard\Repositories\PotentialProduct\PotentialProductRepository;
use Vanguard\Repositories\QueueProcess\QueueProcessRepository;

class PotentialProduct extends BaseController
{
    /**
     * @var ProductsRepository
     */
    private $products;

    private $queueproc;

    /**
     * UsersController constructor.
     * @param UserRepository $users
     */
    public function __construct(PotentialProductRepository $products, QueueProcessRepository $queueproc)
    {
        $this->middleware('auth');
        $this->middleware('session.database', ['only' => ['sessions', 'invalidateSession']]);
        $this->middleware('permission:users.manage');
        $this->products = $products;
        $this->queueproc = $queueproc;
    }


    /**
     * Display paginated list of all products.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $products = $this->products->paginate(
            $perPage = 20,
            Input::get('search'),
            Input::get('status')
        );

        return view('potential_product.list', compact('products'));
    }

    /**
     * fetch potential products
     */
    public function collect($handle = 'queue')
    {
        if ($handle == 'queue') {
            $procObj = $this->queueproc->create([
                'queue_type' => 'fetch',
                'status' => 0,
            ]);
            // use queue to handle
            PotentialFetchJob::dispatch($this->products, $procObj)->onQueue('fetch');
            return $this->success(['queue_process_id' => $procObj->id]);
        } else {
            try {
                $affectRow = $this->products->fetchProducts();
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
            return $this->success(['affectRow' => $affectRow]);
        }
    }

    /**
     * fetch progress
     */
    public function process()
    {
        return $this->success($this->queueproc->find(Input::get('id')));
    }
}
