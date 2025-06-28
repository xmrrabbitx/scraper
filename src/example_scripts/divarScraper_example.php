<?php
/**
 * example usage of Divar data scraping
 */
require 'vendor/autoload.php';

use Rabbit\Scraper\divar\divarApi;

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
