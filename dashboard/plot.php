<?php

include "../vendor/autoload.php";

use Scraper\Trader\scripts\analyser;
use Scraper\Trader\scripts\scraper;

// html entities
print("

    <a href='./dashboard.php' id=''>Dashboard Page</a>
    </br>
    </br>
    <h2>Plot Page</h2>
");

$analyser = new analyser();
$types = $analyser->getTypeProducts("Divar");
if (!empty($types)) {
    print("Divar:");
    foreach ($types as $type) {
         print("
           <div>
              <button class='type_' id=$type>$type</button>
              </br>
              </br>
           </div>
        ");
    }
}

// js entities
print("
    <script>
        let scraperTypes = document.querySelectorAll('.type_');
        
        scraperTypes.forEach(function(element) {
            element.addEventListener('click', function(event) {
                let id = element.id;
                
            });
        });
        
    </script>
    
");