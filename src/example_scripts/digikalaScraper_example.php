<?php
/**
 * example usage of digikala supermarket data scraping
 */
require '../../vendor/autoload.php';

use Rabbit\Scraper\digikala\supermarket\spDigikalaApi;

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

$digikala = new spDigikalaApi('127.0.0.1:8082');
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
        var_dump($json->data->products[0]->title_fa);
    }else {
        echo $categoryName . ": Failed - " . $response['reason'];
    }
}
