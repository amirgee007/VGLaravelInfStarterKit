<?php

namespace Vanguard\Repositories\Crawl;

use Vanguard\Models\Crawl\PotentialProducts;

class EloquentPotentialProduct implements PotentialProductRepository
{

    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        return PotentialProducts::find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data)
    {
        PotentialProducts::create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function paginatePotentialProducts($perPage = 20, $search = null)
    {
        $query = [];
        if ($search) {
            $query = function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('url', 'LIKE', "%$search%")
                    ->orWhere('original_title', 'LIKE', "%$search%");
            };
        }

        $result = PotentialProducts::where($query)
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage);

        if ($search) {
            $result->appends(['search' => $search]);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function lists($search = null, $field = [])
    {
        $query = [];
        if ($search) {
            $query = function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('url', 'LIKE', "%$search%")
                    ->orWhere('original_title', 'LIKE', "%$search%");
            };
        }

        $result = PotentialProducts::where($query)
            ->orderBy('created_at', 'DESC')
            ->select($field)
            ->get()
            ->toArray();

        return $result;
    }
}