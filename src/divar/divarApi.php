<?php

namespace Scraper\Trader\divar;

use Scraper\Trader\core\apiRequest;
use function Scraper\Trader\core\utilities\random_user_agent;

class divarApi extends apiRequest
{

    const SEARCH_CATEGORIES = "https://api.divar.ir/v8/postlist/w/search";

    protected $defaultHeaders = [
        'Referer'=> 'https://divar.ir/',
        'X-Render-Type'=> 'CSR',
        'X-Standard-Divar-Error'=>'true',
        'Content-Type'=> 'application/json',
        //'Accept-Encoding'=> 'gzip, deflate, br',
        'Accept'=> 'application/json, text/plain, */*',
        'Accept-Language'=> 'en,fa;q=0.9,en-US;q=0.8',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->defaultHeaders['User-Agent'] = random_user_agent();
    }
    public function cloth():void
    {
        $data = [
            "city_ids"=>["1"],
            "source_view"=>"CATEGORY",
            "disable_recommendation"=>false,
            "search_data"=>[
                "form_data"=>[
                    "data"=>[
                        "category"=>[
                            "str"=>[
                                "value"=>"clothing"
                            ]
                        ]
                    ]
                ]
            ],
            "server_payload"=>[
                "@type"=>"type.googleapis.com/widgets.SearchData.ServerPayload",
                "additional_form_data"=>[
                    "data"=>[
                        "sort"=>[
                            "str"=>[
                                "value"=>"sort_date"
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $headers = [

        ];
        $rsp = $this->request('POST',self::SEARCH_CATEGORIES, $data, $headers);
        $json = json_decode($rsp);

        var_dump($json);
    }

    public function parseExport()
    {
        var_dump("test is here!");
    }

    /**
     * @param $body
     * @return void
     */
    public function checkResponseErrors($body)
    {
        //
    }


    /**
     * @param string $method
     * @param string $url
     * @param array|null $data
     * @param array|null $headers
     * @return string
     */
    private function request(string $method, string $url, array $data = null, array $headers = null): string
    {
        $headers = array_merge($this->defaultHeaders, $headers ?? []);
        $data = $data !== null ? ['json' => $data] : null;

        $rsp = $this->submitRequest($method, $url, $data, $headers);

        $body = (string)$rsp->getBody();
        $this->checkResponseErrors($body);

        return $body;
    }
}