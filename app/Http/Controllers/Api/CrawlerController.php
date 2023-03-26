<?php
/**
 * @desc       Crawler commodity information
 * @author     WenMing<st-m1ng@163.com>
 * @date       2023-03-20 10:09
 */
namespace Vanguard\Http\Controllers\Api;

use Vanguard\Jobs\Notice;
use Vanguard\Repositories\PotentialProducts\PotentialProductsRepository;
use Vanguard\Repositories\User\UserRepository;
use Illuminate\Http\Request;

class CrawlerController extends ApiController
{
    /**
     * @var PotentialProductsRepository
     */
    private $potentialProducts;
    private $userRepository;

    public function __construct(PotentialProductsRepository $potentialProducts, UserRepository $userRepository)
    {
        $this->potentialProducts = $potentialProducts;
        $this->userRepository = $userRepository;
    }

    /**
     * @desc
     * @return \Illuminate\Http\JsonResponse
     */
    public function publish(Request $request)
    {
        $this->dispatch((new Notice($this->potentialProducts, $this->userRepository, $request->input('uid')))->onQueue('crawler_queue'));
        return $this->respondWithArray(['code' => 200, 'msg' => 'success', 'data' => []]);
    }
}