<?php

include "../vendor/autoload.php";

use Scraper\Trader\scripts\analyser;


// html entities
print("

    <a href='./plot.php' id='plotPage'>plot Page</a>
    <a href='./scraper.php' id='scraperPage'>scraper Page</a>
    

");


// js entities
print("
    <script>
        let plotPage = document.getElementById('plotPage')
        plotPage.addEventListener('click', function (){
            
            console.log('click'); 
        });
    </script>
    

");
