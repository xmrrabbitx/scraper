### scraper Divar and Digikala Supermarket _ just for learning purposes
#### how to scrape?
#### add this line to composer.json 
``` 
    "require": {
        "rabbyte/scraper": "dev-master"
    }
```
#### then ```composer require rabbyte/scraper```
#### supported categories Divar for now:
```<?php
<?php
/**
 * example usage of Divar data scraping
 */
require 'vendor/autoload.php';

use Rabbyte\Scraper\divar\divarApi;

$categories = [
    'stationery',
    "clothing",
    "health-beauty",
    "rhinestones",
    "shoes-belt-bag",
    "childrens-clothing-and-shoe"
];
function scrape($categories, $layerPage, $filterPrice)
{
    if(!empty($categories)) {
        $divar = new divarApi();
        $promises = [];
        foreach ($categories as $category) {
            $asyncCategory = $divar->asyncStruct($category, $layerPage);

            $promises[$category] = $asyncCategory;

        }

        if (isset($promises)) {
            // Run requests concurrently
            $results = $divar->asyncRequest($promises);
            // Process responses
            foreach ($results as $categoryName => $response) {
                if ($response['state'] === 'fulfilled') {

                    $rsp = $response['value']->getBody();
                    $status = $divar->parseExport($filterPrice, $categoryName . "/simple/", $rsp);

                    // next layer date ads
                    if (!$status) {
                        $categories = array_filter($categories, function ($value) use (&$categoryName) {
                            return $value !== $categoryName; // Keeps all elements except $categoryName
                        });
                    }
                } else {
                    echo $categoryName . ": Failed - " . $response['reason'];
                }
            }

            sleep(5);
            $layerPage++;

            scrape($categories, $layerPage, $filterPrice);

        }
    }
}

scrape($categories, 0, 10000000);

```
#### supported categories Digikala Supermarket for now:
```<?php
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
        
        echo $json->data;
        
    }else {
        echo $categoryName . ": Failed - " . $response['reason'];
    }
}

```