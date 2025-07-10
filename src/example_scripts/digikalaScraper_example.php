<?php
/**
 * example usage of digikala supermarket data scraping
 */
require 'vendor/autoload.php';

use Rabbyte\Scraper\digikala\supermarket\spDigikalaApi;

$categories = [
    'oil',
    'chocolate-and-cocoa-products',
    'rice',
    'spaghetti-pasta',
    'sugar',
    'sugar-candy',
    'cereals',
    'bread',
    'types-paste'
];

$digikala = new spDigikalaApi('127.0.0.1:8080');
$promises = [];
foreach ($categories as $category) {
    $asyncCategory = $digikala->asyncStruct($category, 1);

    $promises[$category] = $asyncCategory;

}

$results = $digikala->asyncRequest($promises);
foreach ($results as $categoryName => $response) {
    if ($response['state'] === 'fulfilled') {
        $rsp = (string)$response['value']->getBody();
        $json = json_decode($rsp);

        echo $json->data;

    }else {
        echo $categoryName . ": Failed - " . $response['reason'];
    }
}
