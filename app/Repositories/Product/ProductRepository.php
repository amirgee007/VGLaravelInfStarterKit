<?php

namespace Vanguard\Repositories\Product;

interface ProductRepository
{
    /**
     * Create $key => $value array for all available products.
     *
     * @param string $column
     * @param string $key
     * @return mixed
     */
    public function lists($column = 'original_title', $key = 'id');

    /**
     * Get all available products.
     * @return mixed
     */
    public function all();

    /**
     * Finds the product by given id.
     *
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * Creates new product from provided data.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Updates specified product.
     *
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data);

    /**
     * Remove specified product from repository.
     *
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * Paginate all product records.
     *
     * @param int $perPage
     * @param null $search
     * @return Paginator
     */
    public function paginateProducts($perPage = 20, $search = null);

    /**
     * return product filtered by field
     * @param $field
     * @param $value
     * @return mixed
     */
    public function findByField($field, $value);


}
