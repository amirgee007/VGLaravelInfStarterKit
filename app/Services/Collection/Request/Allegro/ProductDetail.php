<?php

namespace Vanguard\Services\Collection\Request\Allegro;

use Vanguard\Services\Collection\Parser\Allegro\ProductDetailParser;

/**
 * request of home products list
 */
class ProductDetail extends Base
{
    // Url
    public $url;

    // Request method
    public $method = "GET";

    // Parser
    public $parser = ProductDetailParser::class;

    // Payload
    public $payload;
}
