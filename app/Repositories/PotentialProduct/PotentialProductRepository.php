<?php

namespace Vanguard\Repositories\PotentialProduct;
use Vanguard\PotentialProduct;

interface PotentialProductRepository
{
    /**
     * Paginate potential products
     *
     * @param $perPage
     * @param null $search
     * @param null $status
     * @return mixed
     */
    public function paginate($perPage, $search = null);

    /**
     * Find user by its id.
     *
     * @param $id
     * @return null|PotentialProduct
     */
    public function find($id);

    /**
     * Create new potential products.
     *
     * @param array $data
     * @param $condition
     * @return mixed
     */
    public function create($data);




}