<?php
/**
 * @desc
 * @author     WenMing<st-m1ng@163.com>
 * @date       2023-03-20 11:42
 */
namespace Vanguard\Repositories\PotentialProducts;

use Vanguard\PotentialProducts;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class EloquentPotentialProducts implements PotentialProductsRepository
{
    /**
     * {@inheritdoc}
     */
    public function all($search, string $fields)
    {
        $query = PotentialProducts::query();
        if ($search) {
            if(is_numeric($search)){
                $query->where('id', '=', $search);
            }else{
                $query->orWhere('original_title', 'LIKE', "%$search%");
                $query->orWhere('url', 'LIKE', "%$search%");
            }
        }
        return $query->selectRaw($fields)->get()->toArray();
    }

    /**
     * @desc
     * @param $perPage
     * @param $search
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginatePotentialProducts($perPage = 20, $search = null)
    {
        $query = PotentialProducts::query();

        return $this->paginateAndFilterResults($perPage, $search, $query);
    }

    /**
     * @desc
     * @param int     $perPage
     * @param string  $search
     * @param Builder $query
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    private function paginateAndFilterResults(int $perPage, $search, Builder $query)
    {
        if ($search) {
            if(is_numeric($search)){
                $query->where('id', '=', $search);
            }else{
                $query->orWhere('original_title', 'LIKE', "%$search%");
                $query->orWhere('url', 'LIKE', "%$search%");
            }
        }

        $result = $query->orderBy('created_at', 'DESC')
            ->paginate($perPage);

        if ($search) {
            $result->appends(['search' => $search]);
        }

        return $result;
    }

    /**
     * @desc  save crawler data
     * @param $thumbnail
     * @param $url
     * @param $originalTitle
     * @param $originalDescription
     * @param $price
     * @param $image
     * @param $extraImages
     *
     * @return int
     */
    public function saveCrawlerData($thumbnail, $url, $originalTitle, $originalDescription, $price, $image = '', $extraImages = ''){
        return PotentialProducts::query()->insertGetId([
            'thumbnail' => $thumbnail,
            'url' => $url,
            'original_title' => $originalTitle,
            'original_description' => $originalDescription,
            'price' => $price,
            'image' => $image,
            'extra_images' => $extraImages,
            'created_at' => Carbon::now()
        ]);
    }

    /**
     * @desc  update crawler data
     * @param $productId
     * @param $image
     * @param $extraImages
     * @param $description
     */
    public function updateCrawlerData($productId, $image, $extraImages, $description){
        PotentialProducts::query()->where('id', $productId)->update([
            'image' => $image,
            'extra_images' => $extraImages,
            'original_description' => $description,
            'updated_at' => Carbon::now()
        ]);
    }
}
