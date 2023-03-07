<?php

namespace Vanguard\Services\Collection\Request\Allegro;

use Vanguard\Services\Collection\Request;

/**
 * Base request
 */
class Base extends Request
{
    public function __construct(string $url = '', string $method = 'GET')
    {
        parent::__construct($url, $method);
        // auto set header.
        $this->autoSetHeader();
    }


    // auto padding header
    public function autoSetHeader() {
        // cookie file
        $filepath = __DIR__.'/header';
        $headers = [];
        if (file_exists($filepath)) {
            $content = file_get_contents($filepath);
            $cookies = explode("\n", $content);
            foreach ($cookies as $cookie) {
                if (!empty($cookie) && strpos($cookie, ': ') !== false) {
                    list($k, $v) = explode(': ', $cookie);
                    $headers[$k] = trim($v);
                }
            }
        }
        $this->headerParams = $headers;
    }
}
