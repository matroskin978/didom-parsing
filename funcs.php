<?php

function get_html($url, \GuzzleHttp\Client $client): string
{
    $resp = $client->get($url);
    return $resp->getBody()->getContents();
}

function get_pages_count(\DiDom\Document $document): int
{
    $pagination = $document->find('.pagination-list a.pagination-link');
    if (count($pagination) > 1) {
        return $pagination[count($pagination)-2]->text();
    } else {
        return 1;
    }
}

function get_products(\DiDom\Document $document, \GuzzleHttp\Client $client): array
{
    static $product_cnt = 1;

    $products_data = [];
    $products = $document->find('article.card');
    foreach ($products as $product) {
        /*if ($product_cnt > 3) {
            break;
        }*/
        sleep(rand(1, 3));
        echo "product {$product_cnt}...\n";
        $url = $product->first('h4.card-title a')->attr('href');
        $products_data[$product_cnt] = get_product($document, $client, $url, $product_cnt);
        $product_cnt++;
    }
    return $products_data;
}

function get_product(\DiDom\Document $document, \GuzzleHttp\Client $client, $url, $product_cnt): array
{
    global $page_title;

    $file = get_html($url, $client);
    $document->loadHtml($file);
    $product['title'] = $document->first('h1.productView-title')->text();
    $product['price'] = $document->first('div.productView-price span.price.price--withoutTax')->text();
    $product['desc'] = trim($document->first('#productView-descriptions #tab-description')->innerHtml());

    // Image
    $image_path = $document->first('.productView-images img.productView-image--default')->attr('src');
    $product['image'] = $product_cnt . "." . get_ext($image_path);
    if (!is_dir("images/{$page_title}")) {
        mkdir("images/{$page_title}");
    }
    file_put_contents("images/{$page_title}/{$product['image']}", file_get_contents($image_path));

    // Options
    if ($document->has('#productView-descriptions #tab-additional-information table')) {
        $ch_k = $document->first('#productView-descriptions #tab-additional-information table')->find('tr th');
        $ch_v = $document->first('#productView-descriptions #tab-additional-information table')->find('tr td');
        $ch = [];
        foreach ($ch_k as $k => $item) {
            if (empty($ch[$item->text()])) {
                $ch[$item->text()] = $ch_v[$k]->text();
            } else {
                $ch[$item->text()] .= ", {$ch_v[$k]->text()}";
            }
        }
        $product['ch'] = $ch;
    }

    return $product;
}

function get_ext($file_name)
{
    $data = explode('.', $file_name);
    $data = explode('?', end($data));
    return $data[0];
}
