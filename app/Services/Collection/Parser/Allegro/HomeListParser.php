<?php

namespace Vanguard\Services\Collection\Parser\Allegro;

use Symfony\Component\DomCrawler\Crawler;
use Vanguard\Services\Collection\Parser;

/**
 * parser class
 */
class HomeListParser
{
    // parse request result
    public static function parse($data)
    {
        $crawler = new Crawler($data);

        $list = [];
        $crawler->filterXPath('//div[contains(@class,"carousel-item")]/div[2]/ul/li[1]/div')
            ->each(function (Crawler $node, $i) use (&$list) {
                $list[$i]['rank'] = $i;
                $list[$i]['price'] = $node->text();
            });
        $crawler->filterXPath('//div[contains(@class,"carousel-item")]/div[2]/ul/li[3]/a')
            ->each(function (Crawler $node, $i) use (&$list) {
                $list[$i]['original_title'] = $node->text();
                $list[$i]['url'] = $node->attr('href');

            });
        $crawler->filterXPath('//div[contains(@class,"carousel-item")]/div[1]/div/img')
            ->each(function (Crawler $node, $i) use (&$list) {
                $list[$i]['thumbnail'] = $node->attr('data-src');
            });
        return $list;
    }
}
