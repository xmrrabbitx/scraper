<?php

require '../../vendor/autoload.php';

use Scraper\Trader\divar\divarApi;

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
                    var_dump($categoryName);
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

/*
// html entities
print("

    <a href='./dashboard.php' id=''>Dashboard Page</a>
    </br>
    </br>
    <h2>Scraper Page</h3>
");

print("<h3>Divar</h3>");
$list = ['cloth'];
foreach ($list as $types){
    print("

        <span id='divar_$types' class='type_'>$types</span>
        <label> ___ major search</label>
        <input type='checkbox' id='divar_{$types}_major' value='major'>
        
    ");
}

// js entities
print("
    <script>
        let scraperTypes = document.querySelectorAll('.type_');
        
        scraperTypes.forEach(function(element) {
            element.addEventListener('click', function(event) {
                
                let className = element.id.split('_')[0];
                let functionName = element.id.split('_')[1];
                let type = document.getElementById(element.id + '_major').checked;
                
                if(type){
                    type = 'major';
                }else{
                    type = 'simple';
                }
                
                let cityName = document.getElementById(element.id + '_cityName')?.value ?? null;
                let date = document.getElementById(element.id + '_date')?.value ?? null;
                
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '../src/scripts/scraper.php');
                
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.setRequestHeader('Connection', 'Keep-Alive');
                xhr.setRequestHeader('Keep-Alive', 'timeout=55, max=200');
               
                
                const data = new URLSearchParams();
                data.append('className', className);
                data.append('functionName', functionName);
                data.append('type', type);
                data.append('cityName', cityName);
                data.append('date', date);
                
                xhr.send(data);
                
                xhr.onload = function(resp) {
                  
                };
            });
        });
        
    </script>
    
");
*/