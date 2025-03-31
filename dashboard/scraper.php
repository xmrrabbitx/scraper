<?php

require '../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Promise;

$client = new Client([
    'base_uri'=> '',
    'http_errors' => false,
    'timeout' => 10,
    'proxy'=>'http://127.0.0.1:8080',
    'verify'=> false,
    'allow_redirects' => [
        'max' => 25,
        'track_redirects' => true,
    ],
]);
$req = $client->getAsync('https://google.com');
$promises = [
    'request1' => $req,
    'request2' => $client->getAsync('https://yahoo.com'),
];

// Run requests concurrently
$results = Promise\Utils::settle($promises)->wait();

// Process responses
foreach ($results as $key => $response) {
    if ($response['state'] === 'fulfilled') {
       // echo $key . ": " . $response['value']->getBody();
    } else {
       // echo $key . ": Failed - " . $response['reason'];
    }
}



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