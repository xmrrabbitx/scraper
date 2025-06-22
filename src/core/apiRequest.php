<?php

namespace Rabbit\Scraper\core;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Promise;

/**
 * @property HttpClient $httpClient
 */
abstract class apiRequest
{

    private HttpClient $httpClient;

    public function __construct(string $proxy='')
    {
        $this->httpClient = new HttpClient([
            'base_uri'=> '',
            'http_errors' => false,
            'timeout' => 10,
            'proxy'=>$proxy,
            'verify'=> false,
            'allow_redirects' => [
                'max' => 25,
                'track_redirects' => true,
            ],
        ]);
    }

    /**
     * @param $method
     * @param $url
     * @param $data
     * @param $headers
     */
    public function submitRequest($method, $url, $data, $headers)
    {
        $options = array_merge(
            ['headers'=> $headers],
            $data ?? [],
            ['on_stats'=> function ($transferStats) use (&$stats) { $stats = $transferStats; }]
        );

       return $this->httpClient->request($method, $url, $options);

    }

    /**
     * async POST object
     * @param $method
     * @param $url
     * @param $data
     * @param $headers
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function asyncPostRequest($url, $data, $headers)
    {
        $options = array_merge(
            ['headers'=> $headers],
            $data ?? [],
            ['on_stats'=> function ($transferStats) use (&$stats) { $stats = $transferStats; }]
        );
        return $this->httpClient->postAsync($url, $options);
    }

    /**
     * async GET object
     * @param $url
     * @param $data
     * @param $headers
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function asyncGetRequest($url, $data, $headers)
    {
        $options = array_merge(
            ['headers'=> $headers],
            $data ?? [],
            ['on_stats'=> function ($transferStats) use (&$stats) { $stats = $transferStats; }]
        );
        return $this->httpClient->getAsync($url, $options);
    }

    /**
     * @param $promises
     * @return mixed
     */
    public function asyncSubmitRequest($promises)
    {
        return Promise\Utils::settle($promises)->wait();
    }
}