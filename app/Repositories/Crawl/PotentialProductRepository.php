<?php

namespace Vanguard\Repositories\Crawl;

use Illuminate\Contracts\Pagination\Paginator;
use Vanguard\Models\Crawl\PotentialProducts;

interface PotentialProductRepository
{
    /**
     * Find PotentialProducts by its id.
     *
     * @param $id
     * @return null|PotentialProducts
     */
    public function find($id);

    /**
     * Create new Potential Product.
     *
     * @param array $data
     */
    public function create(array $data);

    /**
     * Paginate all potential product records.
     *
     * @param int $perPage
     * @param null $search
     * @return Paginator
     */
    public function paginatePotentialProducts($perPage = 20, $search = null);

    /**
     * All potential product records.
     *
     * @param null   $search
     * @param array $field
     * @return mixed
     */
    public function lists($search = null, $field = []);
}