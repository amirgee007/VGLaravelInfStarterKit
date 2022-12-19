<?php

namespace Vanguard\Repositories\Product;

use Vanguard\Product;
use Cache;

class EloquentProduct implements ProductRepository
{
    /**
     * {@inheritdoc}
     */
    public function lists($column = 'original_title', $key = 'id')
    {
        return Product::orderBy('id')->pluck($column, $key);
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return Product::all();
    }

    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        return Product::find($id);
    }


    /**
     * {@inheritdoc}
     */
    public function create(array $data)
    {
        $product = Product::create($data);
//        event(new Created($product));

        return $product;
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data)
    {
        $product = $this->find($id);

        $product->update($data);

        Cache::flush();

//        event(new Updated($product));

        return $product;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        $product = $this->find($id);

//        event(new Deleted($product));

        $status = $product->delete();

        Cache::flush();

        return $status;
    }

    /**
     * {@inheritdoc}
     */
    public function paginateProducts($perPage = 20, $search = null)
    {
        $query = Product::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
//                $q->where('title', "like", "%{$search}%");
                $q->orWhere('original_title', 'like', "%{$search}%");
            });
        }

        $result = $query->orderBy('id', 'asc')
            ->paginate($perPage);

        if ($search) {
            $result->appends(['search' => $search]);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function findByField($field, $value)
    {
       return Product::where($field, $value)->first();
    }

}
