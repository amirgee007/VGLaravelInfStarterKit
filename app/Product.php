<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'potential_products';

    protected $casts = [
        'removable' => 'boolean'
    ];
    public $timestamps = false;

    protected $fillable = ['rank', 'price', 'original_title', 'thumbnail', 'url', 'original_description', 'extra_images', 'image', 'english_title', 'english_description',
        'chinese_title', 'chinese_description', 'created_at', 'updated_at'];
}
