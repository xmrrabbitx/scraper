<?php

namespace Rabbyte\Scraper\digikala\supermarket;

use Rabbyte\Scraper\core\apiRequest;
use Rabbyte\Scraper\exceptions\SiteException;
use function Rabbyte\Scraper\core\utilities\random_user_agent;

/**
 * a class to retrive Digikala supermarket data
 */
class spDigikalaApi extends apiRequest
{
    protected array $session;

    const SEARCH_CATEGORIES = "https://api.digikala.com/fresh/v1/categories/%s/search/?_whid=1&sort=1&seo_url=/category-oil/?sort=1&page=%s";


    const FILE_PATH = "../xls/%s";

    const SCRIPT_NAME = "Digikala/";

    protected $defaultHeaders = [
        'access-control-allow-origin'=> 'https://www.digikala.com',
        'Content-Type'=> 'application/json',
        'Accept'=> 'application/json, text/plain, */*',
        'Accept-Language'=> 'en,fa;q=0.9,en-US;q=0.8',
    ];

    public function __construct(string $proxy='')
    {
        parent::__construct($proxy);
        $this->defaultHeaders['User-Agent'] = random_user_agent();
    }

    /**
     * @param string|null $cityName
     * @param int $layerPage
     * @param int $filterPrice filter prices
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function asyncStruct(string $category, int $layerPage)
    {
        try {
            $url = sprintf(self::SEARCH_CATEGORIES, $category, $layerPage);
            return $this->request('GET', $url);

        }catch (\Exception $error){

        }

    }

    public function asyncRequest($promises)
    {
        return $this->asyncSubmitRequest($promises);
    }

    /**
     * @param $body
     * @return void
     * @throws \Exception
     */
    public function checkResponseErrors($body)
    {
       // $json = json_decode($body);
       // if(isset()){
           // throw new \Exception();
        //}
    }


    /**
     * @param string $method
     * @param string $url
     * @param array|null $data
     * @param array|null $headers
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    private function request(string $method, string $url, array $data = null, array $headers = null): \GuzzleHttp\Promise\PromiseInterface
    {
        $headers = array_merge($this->defaultHeaders, $headers ?? []);
        $data = $data !== null ? ['json' => $data] : null;

        if($method === "GET"){
            return $this->asyncGetRequest($url, $data, $headers);
        }elseif ($method === "POST") {
            return $this->asyncPostRequest($url, $data, $headers);
        }

    }

}