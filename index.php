<?php

set_time_limit(0);
ini_set('memory_limit', -1);
//$url = 'https://www.englishteastore.com';

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/funcs.php';

if (!empty($argv[1])) {
    $client = new \GuzzleHttp\Client();
    $document = new \DiDom\Document();
    $url = $argv[1];

    echo "Start parsing...\n";
    $file = get_html($url, $client);
    $document->loadHtml($file);

    $page_title = $document->first('h1.page-heading')->text();

    $pages_count = get_pages_count($document);
    $products_data = [];

    for ($i = 1; $i <= $pages_count; $i++) {
        echo "PAGE PARSING {$i} of {$pages_count}...\n";
        sleep(rand(1, 3));

        if ($i > 1) {
            if (parse_url($url, PHP_URL_QUERY)) {
                $url .= "&sort=featured&page={$i}";
            } else {
                $url .= "?sort=featured&page={$i}";
            }
            $file = get_html($url, $client);
            $document->loadHtml($file);
        }

        $products_data = array_merge($products_data, get_products($document, $client));
    }

    $pr_cnt = count($products_data);
//    print_r($products_data);
    file_put_contents("{$page_title}.json", json_encode($products_data));

    echo "\n==========================\n";
    echo "Complited! Items received: {$pr_cnt}";
}
