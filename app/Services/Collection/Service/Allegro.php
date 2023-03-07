<?php

namespace Vanguard\Services\Collection\Service;

use Vanguard\Services\Collection\Client;
use Vanguard\Services\Collection\Request\Allegro\HomeList;
use Vanguard\Services\Collection\Request\Allegro\ProductDetail;

/**
 * service class
 */
class Allegro
{
    // parse request result
    public static function getProducts()
    {
        $requst = new HomeList();
        $response = Client::getInstance()->execute($requst);
        $listRes = $response->getPayload();
        $result = array();
        foreach ($listRes as $item) {
            if (!empty($item['url'])) {
                $requst = new ProductDetail($item['url']);
                $response = Client::getInstance()->execute($requst);
                $payload = $response->getPayload();
                $result[] = array_merge($item, $payload);
            }
        }
        foreach ($result as &$it) {
            $it['extra_images'] = implode(',', $it['extra_images']);
        }
        return $result;
    }
}
