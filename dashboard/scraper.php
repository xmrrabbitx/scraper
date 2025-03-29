<?php

include "../vendor/autoload.php";

use Scraper\Trader\scripts\analyser;
use Scraper\Trader\scripts\scraper;

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
    print("<span id='Divar_$types' class='type_'>$types</span>");
}

// js entities
print("
    <script>
        let scraperTypes = document.querySelectorAll('.type_');
        
        scraperTypes.forEach(function(element) {
            element.addEventListener('click', function(event) {
                let id = element.id;
                let xhr = new XMLHttpRequest();
                xhr.open('GET', '../src/scripts/scraper.php');
                xhr.send();
                
                xhr.onload = function(resp) {
                  
                };
            });
        });
        
    </script>
    
");