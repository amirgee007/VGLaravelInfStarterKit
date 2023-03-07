<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Model;

class PotentialProduct extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'potential_products';

    protected $fillable = [
        'thumbnail', 'url', 'original_title', 'original_description', 'price', 'english_title', 'english_description',
        'chinese_title', 'chinese_description', 'image', 'extra_images', 'created_at', 'updated_at'
    ];

}
