<?php
/**
 * example usage of torob data scraping
 */
require 'vendor/autoload.php';

use Rabbyte\Scraper\torob\torob;

$categories = [
    'mobile'
];

$brands = [
  'apple', 'xiaomi','samsung'
];

$sort = [
    '', // sort based on 'محبوب ترین'
    'price', // sort based on 'ارزان ترین'
    '-price', // sort based on 'گران ترین'
    '-date' // sort based on 'جدیدترین'
];

$digikala = new torob('127.0.0.1:8080');
$promises = [];
for ($i=0;$i<count($brands); $i++) {

    $asyncCategory = $digikala->asyncStruct($categories[0], $brands[$i], $sort[$i],  2);

    $promises[$brands[$i]] = $asyncCategory;

}

$results = $digikala->asyncRequest($promises);
foreach ($results as $categoryName => $response) {
    if ($response['state'] === 'fulfilled') {
        $rsp = (string)$response['value']->getBody();
        $json = json_decode($rsp);

        var_dump($json);

    }else {
        echo $categoryName . ": Failed - " . $response['reason'];
    }
}
