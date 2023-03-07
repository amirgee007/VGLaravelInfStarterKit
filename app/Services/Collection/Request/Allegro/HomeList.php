<?php

namespace Vanguard\Services\Collection\Request\Allegro;

use Vanguard\Services\Collection\Parser\Allegro\HomeListParser;

/**
 * request of home products list
 */
class HomeList extends Base
{
    // Url
    public $url = "https://allegro.pl/";

    // Request method
    public $method = "GET";

    // Parser
    public $parser = HomeListParser::class;

    // Payload
    public $payload;
}
