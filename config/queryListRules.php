<?php
return [
    //https://allegro.pl/   https://www.fruugo.us/
    //These websites always get access denied or 403 forbidden when i crawl.That maybe my IP address problem or they used javascript to generate the data.It also could be other reasons that needs more analyses.
    //I could use proxy server to solve this problem but it is not free and unstable.
    //So i use this url to make an example for showing how to crawl data. Nevertheless, the images from this website get some problems that can`t be displayed correctly.
    //But i still saved them into database. I have tried that the image will be displayed if the url address is readable.
    'targetUrl' => 'https://www.abebooks.de/servlet/SearchResults?kn=J.%20K.%20Rowling&sts=t&cm_sp=SearchF-_-topnav-_-Results&ds=20',
    'potentialProductsRule' => [
        'thumbnail'=> ['.result-image img', 'src'],
        'url' => ['.result-detail a', 'href', '', function($url){
            return 'https://www.abebooks.de'.$url;
        }],
        'original_title' => ['h2>a>span', 'text'],
        'original_description' => ['.desc-container>.readmore-toggle', 'text'],
        'price' => ['.item-price', 'text'],
    ],
    'potentialProductsSubRule' => [
        'english_title' => ['#biblio-title', 'text'],
        'english_description' => ['.review-body', 'text'],
    ],
    'potentialProductsRange' => '.result-block li',
    'potentialProductsSubRange' => '.seller-product-details',
];