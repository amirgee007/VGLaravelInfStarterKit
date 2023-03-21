<?php

namespace Vanguard\Models\Crawl;

use Illuminate\Database\Eloquent\Model;

class PotentialProducts extends Model
{
    protected $table = 'potential_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rank','thumbnail','url','original_title','original_description','price','english_title',
        'english_description', 'chinese_title','chinese_description','image','extra_images'
    ];
}