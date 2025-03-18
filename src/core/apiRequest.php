<?php

namespace Scraper\Trader\core;

use GuzzleHttp\Client as HttpClient;

/**
 * @property HttpClient $httpClient
 */
abstract class apiRequest
{

    private HttpClient $httpClient;

    public function __construct()
    {
        $this->httpClient = new HttpClient([
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
    }

    public function submitRequest($method, $url, $data, $headers)
    {
        $options = array_merge(
            ['headers'=> $headers],
            $data ?? [],
            ['on_stats'=> function ($transferStats) use (&$stats) { $stats = $transferStats; }]
        );

       return $this->httpClient->request($method, $url, $options);

    }
}