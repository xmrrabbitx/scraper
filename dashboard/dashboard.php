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
    <script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js'></script>
    <canvas id='myChart' width='400' height='400'></canvas>
    <script>
    const ctx = document.getElementById('myChart').getContext('2d');
    const data = {
                labels: ['January', 'February', 'March', 'April', 'May'],
                datasets: [{
                    label: 'My Dataset',
                    data: [65, 59, 80, 81, 56],
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            };
     const chart = new Chart(ctx, {
          type: 'line',
          data: data,
          options: {
            onClick: (e) => {
              const canvasPosition = getRelativePosition(e, chart);
        
              // Substitute the appropriate scale IDs
              const dataX = chart.scales.x.getValueForPixel(canvasPosition.x);
              const dataY = chart.scales.y.getValueForPixel(canvasPosition.y);
            }
          }
        });
    </script>
    <script>
        let plotPage = document.getElementById('plotPage')
        plotPage.addEventListener('click', function (){
            
            console.log('click'); 
        });
    </script>
    

");
