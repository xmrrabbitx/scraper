<?php

use Scraper\Trader\analysis\analyser;
use function Scraper\Trader\core\utilities\currentDate;
use function Scraper\Trader\core\utilities\gregorian_to_jalali;

include "../vendor/autoload.php";

$analyser = new analyser();
$productCategories = $analyser->getCategoryProductsPlot("Divar");

// html entities
print("

    <a href='./dashboard.php' id=''>Dashboard Page</a>
    </br>
    </br>
    <h2>Plot Page</h2>
    
    <button id='Divar'>Divar</button>
    
    <label>date from:</label>
    <input id='dateFrom'>
    
    <label>date to:</label>
    <input id='dateTo'>
    <h3>Products Categories</h3>
");

foreach ($productCategories as $categories){
    print("
        <div>
            <label>$categories</label>
            <input id='' type='checkbox' value=$categories>
        </div>
    ");
}

// js entities
print("
    <script>
        let scraperDivar = document.getElementById('Divar');
        scraperDivar.addEventListener('click', function (){
            
            let dateFrom = document.getElementById('dateFrom');
            let dateTo = document.getElementById('dateTo');
            if(dateFrom.value === '' && dateTo.value === ''){
                dateFrom = null;
                dateTo = null;
            }else {
                dateFrom = dateFrom.value;
                dateTo = dateTo.value;
            }
            
            let categories = ['clothing','stationery'];
            
            let xhr = new XMLHttpRequest();
            // Configure the request
            xhr.open('POST', '../src/api/analyserApi.php', true); // Change to your endpoint
            xhr.setRequestHeader('Content-Type', 'application/json');
            
            // Set up the response handler
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Parse the response (assuming JSON)
                    let response = JSON.parse(xhr.responseText);
                    console.log(response)
                    let infos = [[], []];
                    response.forEach(function (values, index){
                        values.forEach(function (value){
                           infos[index].push({x:value, y:value}); 
                        });
                    });
                    function getRandomRGB() {
                      const r = Math.floor(Math.random() * 256);
                      const g = Math.floor(Math.random() * 256);
                      const b = Math.floor(Math.random() * 256);
                      return {r,g,b};
                    }
                    
                    let datast = [];
                    infos.forEach(function (values, index){
                      
                        datast.push(
                          {
                                label: categories[index],
                                data: infos[index],
                                fill: true,
                                //borderColor: 'rgb(' + getRandomRGB().r + ',' + getRandomRGB().g + ',' + getRandomRGB().b + ')',
                                backgroundColor: 'rgb(' + getRandomRGB().r + ',' + getRandomRGB().g + ',' + getRandomRGB().b + ')',
                                tension: 0.1
                            }  
                            
                        );
                    });
                    console.log(datast)
                    const ctx = document.getElementById('myChart').getContext('2d');
                    // destroy old chart and create new
                    if (Chart.getChart(ctx)) {
                        Chart.getChart(ctx).destroy();
                    }
                    const data = {
                        labels: ['January', 'February', 'March', 'April', 'May'],
                        datasets: [
                            ...datast
                            ,{
                                label: 'median prices',
                                data: [
                                    { x: 0, y: 20 },
                                    { x: 100, y: 20 }
                                ],
                                type: 'line', // Explicitly set as line
                                borderColor: 'rgb(54, 162, 235)',
                                //backgroundColor: 'rgba(54, 162, 235, 0.1)',
                                //fill: true,
                                tension: 0.4, // Smooth curve
                                pointRadius: 0, // Hide line points (optional)
                            }
                        ]
                    };
                
                    const chart = new Chart(ctx, {
                        type: 'scatter',
                        data: data,
                        options: {
                            scales: {
                              x: {
                                type: 'linear',
                                position: 'bottom'
                              }
                            }
                        }
                    });
                }
            }
            xhr.send(JSON.stringify({
                'dateFrom':dateFrom,
                'dateTo':dateTo,
                'categories':categories
            }));
        });

    </script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js'></script>
    <canvas id='myChart' width='0' height='0'></canvas>
    <script>
        
    </script>
    
");