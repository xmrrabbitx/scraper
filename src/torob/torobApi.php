<?php

namespace Rabbyte\Scraper\torob;

use Rabbyte\Scraper\core\apiRequest;
use Rabbyte\Scraper\exceptions\SiteException;
use function Rabbyte\Scraper\core\utilities\random_user_agent;

/**
 * a class to retrive Torob data
 */
class torobApi extends apiRequest
{
    protected array $session;

    const SEARCH_CATEGORIES = "https://api.torob.com/v4/base-product/search/?page=%s&sort=%s&size=24&category=%s&category_name=%s&brand=%s&brand_name=%s";

    protected $defaultHeaders = [
        'access-control-expose-headers'=>'AMP-Access-Control-Allow-Source-Origin',
        'access-control-allow-origin'=> 'https://torob.com',
        'Content-Type'=> 'application/json; charset=utf-8',
        'Accept'=> '*/*',
        'Accept-Language'=> 'en,fa;q=0.9,en-US;q=0.8',
    ];

    protected $categories = [
        'mobile'=>[
            'config'=>['94','گوشی-موبایل-mobile'],
            'brands'=>[
                'apple'=>['14','apple-اپل'],
                'xiaomi'=>['102','xiaomi-شیایومی'],
                'samsung'=>['5','samsung-سامسونگ'],
            ]
        ]
    ];

    protected $sort = [
        'price',
        '-price',
        '-date'
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
    public function asyncStruct(string $category, string $brand, string $sort, int $layerPage, )
    {
        try {
            $url = sprintf(self::SEARCH_CATEGORIES,
                $layerPage,
                $sort,
                $this->categories[$category]['config'][0],
                $this->categories[$category]['config'][1],
                $this->categories[$category]['brands'][$brand][0],
                $this->categories[$category]['brands'][$brand][1],

            );
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