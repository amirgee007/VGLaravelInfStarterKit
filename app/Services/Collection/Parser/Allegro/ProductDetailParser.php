<?php

namespace Vanguard\Services\Collection\Parser\Allegro;

use Symfony\Component\DomCrawler\Crawler;
use Vanguard\Services\Collection\Parser;

/**
 * parser class
 */
class ProductDetailParser
{
    // parse request result
    public static function parse($data)
    {
        $crawler = new Crawler($data);

        $list = [];
        $crawler->filterXPath('//div[@data-box-name="allegro.showoffer.gallery"]/div/div/div/div[3]/div/div/div/div/div/img')
            ->each(function (Crawler $node, $i) use (&$list) {
                $list['extra_images'][] = $node->attr('src');
            });
        $crawler->filterXPath('//div[@data-box-name="allegro.showoffer.gallery"]/div/div/div/div[1]/div[1]/div/div/div[1]/img')
            ->each(function (Crawler $node, $i) use (&$list) {
                $list['image'] = $node->attr('src');
            });
        $crawler->filterXPath('//div[@data-box-name="Description"]/div')
            ->each(function (Crawler $node, $i) use (&$list) {
                $list['original_description'] = $node->html();
            });

        return $list;
    }
}
