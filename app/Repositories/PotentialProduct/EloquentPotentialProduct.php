<?php

namespace Vanguard\Repositories\PotentialProduct;

use Vanguard\Models\PotentialProduct;


class EloquentPotentialProduct implements PotentialProductRepository
{
    public function __construct()
    {

    }

    public function paginate($perPage, $search = null, $status = null)
    {
        $query = PotentialProduct::query();

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('username', "like", "%{$search}%");
                $q->orWhere('email', 'like', "%{$search}%");
                $q->orWhere('first_name', 'like', "%{$search}%");
                $q->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $result = $query->orderBy('id', 'desc')
            ->paginate($perPage);

        if ($search) {
            $result->appends(['search' => $search]);
        }

        if ($status) {
            $result->appends(['status' => $status]);
        }

        return $result;
    }

    /**
     * find by id
     */
    public function find($id)
    {
        return PotentialProduct::find($id);
    }

    /**
     * find by id
     */
    public function findByTitle($original_title)
    {
        return PotentialProduct::where('original_title', $original_title)->first();
    }

    /**
     * create data
     */
    public function fetchProducts()
    {
        // default fetch site
        $site = 'Allegro';
        $class = "\\Vanguard\\Services\\Collection\\Service\\$site";
        if (!class_exists($class)) {
            throw new \Exception("not support！");
        }
        $result = $class::getProducts();

        $affectRow = 0;
        foreach ($result as $it) {
            if (empty(self::findByTitle($it['original_title']))) {
                PotentialProduct::create($it);
                $affectRow++;
            }
        }
        return $affectRow;
    }

    /**
     * create data
     */
    public function create(array $data)
    {
        return PotentialProduct::create($data);
    }

    public function update($id, array $data)
    {

    }

    public function delete($id)
    {

    }

    public function count()
    {

    }
}
