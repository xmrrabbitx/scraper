<?php

use Scraper\Trader\analysis\analyser;
use function Scraper\Trader\core\utilities\currentDate;
use function Scraper\Trader\core\utilities\gregorian_to_jalali;

include "../vendor/autoload.php";


// html entities
print("

    <a href='./dashboard.php' id=''>Dashboard Page</a>
    </br>
    </br>
    <h2>Plot Page</h2>
    <button id='Divar'>Divar</button>
");

// js entities
print("
    <script>
        let scraperDivar = document.getElementById('Divar');
        scraperDivar.addEventListener('click', function (){
            console.log('click')
            let xhr = new XMLHttpRequest();
            // Configure the request
            xhr.open('POST', '../src/api/analyserApi.php', true); // Change to your endpoint
            xhr.setRequestHeader('Content-Type', 'application/json');
            
            // Set up the response handler
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Parse the response (assuming JSON)
                    let response = JSON.parse(xhr.responseText);
                    let infos = [];
                    response.forEach(function (values){
                        infos.push({x:values, y:values})
                    });
                    const ctx = document.getElementById('myChart').getContext('2d');
                    const data = {
                        labels: ['January', 'February', 'March', 'April', 'May'],
                        datasets: [
                            {
                                label: 'Divar',
                                data: infos,
                                fill: true,
                                borderColor: 'rgb(255, 99, 132)',
                                backgroundColor: 'rgb(255, 99, 132)',
                                tension: 0.1
                            },
                            {
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
                'date':'1404/1/15',
                'category':'clothing'
            }));
        });

    </script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js'></script>
    <canvas id='myChart' width='0' height='0'></canvas>
    <script>
        
    </script>
    
");