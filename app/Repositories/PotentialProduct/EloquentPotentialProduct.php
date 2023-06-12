<?php

namespace Vanguard\Repositories\PotentialProduct;

use Vanguard\PotentialProduct;

class EloquentPotentialProduct implements PotentialProductRepository
{
    public $rule;
    public function __construct()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function paginate($perPage, $search = null)
    {
        return PotentialProduct::when( $search, function($param) use($search){
            $param->where( 'original_title', 'like', "%$search%" )->orwhere( 'english_title',  'like', "%$search%" );
        })->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        return PotentialProduct::find($id);
    }



    /**
     * {@inheritdoc}
     */
    public function create($arr)
    {
        return $arr;
    }






}
