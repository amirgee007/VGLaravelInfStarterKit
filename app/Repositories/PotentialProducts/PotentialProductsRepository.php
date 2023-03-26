<?php
/**
 * @desc       PotentialProductsRepository
 * @author     WenMing<st-m1ng@163.com>
 * @date       2023-03-20 10:35
 */
namespace Vanguard\Repositories\PotentialProducts;

use Illuminate\Contracts\Pagination\Paginator;

interface PotentialProductsRepository
{

    public function all($search, string $fields);

    /**
     * @desc
     * @param int $perPage
     * @param null $search
     *
     * @return Paginator
     */
    public function paginatePotentialProducts($perPage = 20, $search = null);

    public function saveCrawlerData($thumbnail, $url, $originalTitle, $originalDescription, $price, $image = '', $extraImages = '');

    public function updateCrawlerData($productId, $image, $extraImages, $description);

}
