<?php

namespace Scraper\Trader\divar;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Scraper\Trader\core\apiRequest;
use Scraper\Trader\core\General;
use Scraper\Trader\exceptions\SiteException;
use function Scraper\Trader\core\utilities\currentDate;
use function Scraper\Trader\core\utilities\gregorian_to_jalali;
use function Scraper\Trader\core\utilities\random_user_agent;

class divarApi extends apiRequest
{
    protected array $session;

    const SEARCH_CATEGORIES = "https://api.divar.ir/v8/postlist/w/search";

    const FILE_PATH = "../xls/%s";

    const SCRIPT_NAME = "Divar/";

    const CITY_CODES = [

        "tehran"=>"1",
    ];

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

    /**
     * @param string|null $cityName
     * @param int $layerPage
     * @param int $filterPrice filter prices
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function asyncStruct(string $type, int $layerPage, string $queryType = null, string $cityName=null, $filterDate=null, int $filterPrice=10000000)
    {
        try {

            $date = currentDate();
            $date = explode("-", $date);
            $dateShamsi = gregorian_to_jalali($date[0], $date[1], $date[2]);
            $dateShamsi = $dateShamsi[0] . "/" . $dateShamsi[1] . "/" . $dateShamsi[2];
            $this->session['currentDate'] = $dateShamsi;

            // query the major products in the category
            if($queryType === "major") {
                //$this->majorQuery($cityName, $type, "cloth/", $layerPage, $filterPrice, $filterDate);
            }else {
                $data = [
                    "city_ids" => [$cityName ?? self::CITY_CODES['tehran']],
                    "source_view" => "CATEGORY",
                    "disable_recommendation" => false,
                    "search_data" => [
                        "form_data" => [
                            "data" => [
                                "category" => [
                                    "str" => [
                                        "value" => $type
                                    ]
                                ]
                            ]
                        ]
                    ],
                    "pagination_data" => [
                        "@type" => "type.googleapis.com/post_list.PaginationData",
                        "layer_page" => $layerPage, // older ads
                        "page" => $layerPage // older ads
                    ],
                    "server_payload" => [
                        "@type" => "type.googleapis.com/widgets.SearchData.ServerPayload",
                        "additional_form_data" => [
                            "data" => [
                                "sort" => [
                                    "str" => [
                                        "value" => "sort_date"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];

              return $this->request('POST', self::SEARCH_CATEGORIES, $data);

            }

        }catch (\Exception $error){
            //var_dump($error);
        }

    }


    /**
     * return major products query
     * @param string|null $cityName
     * @param string $type
     * @param int $layerPage
     * @param int $filterPrice filter prices
     * @return void
     */
    public function majorQuery(string $cityName=null, string $type, string $categoryName, int $layerPage=0,  int $filterPrice=10000000, $filterDate=null):void
    {
        try {

            $date = currentDate();
            $date = explode("/", $date);
            $dateShamsi = gregorian_to_jalali($date[0], $date[1], $date[2]);
            $dateShamsi = $dateShamsi[0] . "/" . $dateShamsi[1] . "/" . $dateShamsi[2];
            $this->session['currentDate'] = $dateShamsi;

            $data = [
                "city_ids"=>[$cityName ?? self::CITY_CODES['tehran']],
                "source_view"=>"SEARCH",
                "disable_recommendation"=>false,
                "search_data"=>[
                    "form_data"=>[
                        "data"=>[
                            "category"=>[
                                "str"=>[
                                    "value"=> $type
                                ]
                            ]
                        ]
                    ],
                    'query'=>'عمده'
                ],
                "pagination_data"=>[
                    "@type"=>"type.googleapis.com/post_list.PaginationData",
                    "layer_page"=>$layerPage, // older ads
                    "page"=>$layerPage // older ads
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

            $rsp = $this->request('POST', self::SEARCH_CATEGORIES, $data);
            $status = $this->parseExportMajor($filterPrice, "$categoryName/major/", $rsp, $filterDate);

            sleep(5);
            $layerPage++;

            // next layer date ads
            if($status){
                $this->majorQuery($cityName, $type, $categoryName, $layerPage, $filterPrice, $filterDate);
            }

        }catch (\Exception $error){
            //var_dump($error);
        }
    }

    /**
     * @param $rsp
     * @return void
     */
    public function parseExport($filterPrice, $categoryName,  $rsp):bool
    {
        // check if day is ended
        //$cdate = explode("-",currentDate());
        //$currentDate = gregorian_to_jalali($cdate[0],$cdate[1], $cdate[2]);
        //$currentDate = $currentDate[0] . "/" . $currentDate[1] . "/" . $currentDate[2];

        // set current date for file name
        $date = explode("-",currentDate());
        $dateShamsi = gregorian_to_jalali($date[0],$date[1], $date[2]);
        $filePath = $dateShamsi[0]."/".$dateShamsi[1]."/";

        $filePath = sprintf(self::FILE_PATH, self::SCRIPT_NAME . $categoryName . $filePath);

        $fileName = $dateShamsi[2] . ".xls";

        $json = json_decode($rsp);
        $data = $json->list_widgets;
        $info = [];
        // loop the ads data
        foreach ($data as $adsList){

            if($adsList->widget_type === "POST_ROW") {

                if (isset($adsList->data->red_text)) {
                    $ads_owner = "shop";
                } else {
                    $ads_owner = "people";
                }

                $dateMil = explode("T", $adsList->action_log->server_side_info->info->sort_date)[0];
                $time = explode("T", $adsList->action_log->server_side_info->info->sort_date)[1];
                $date = explode("-", $dateMil);
                $dateShamsi = gregorian_to_jalali($date[0], $date[1], $date[2]);
                $dateShamsi = $dateShamsi[0] . "/" . $dateShamsi[1] . "/" . $dateShamsi[2];

                $price = $adsList->data->middle_description_text;
                $unicode = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
                $english = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
                $price = str_replace($unicode, $english, trim($price));
                $price = preg_replace('/[^0-9]/', '', $price);

                if($price !== str_repeat('1', strlen($price)) && $price < $filterPrice) {

                    if($dateMil === currentDate()) {

                        $info[] = [
                            "title" => $adsList->data->action->payload->web_info->title,
                            "description" => $adsList->data->action->payload->web_info->title,
                            "city" => $adsList->data->action->payload->web_info->city_persian,
                            "district" => $adsList->data->action->payload->web_info->district_persian,
                            "date" => $dateShamsi,
                            "time"=>$time,
                            "price" => $price,
                            "ads_owner" => $ads_owner,
                            'token' => $adsList->data->action->payload->token
                        ];
                    }
                }
            }
        }

        // insert into the file
        if (!is_file($filePath . $fileName)) {

                // create an instance of Spreadsheet()
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // set columns titles
                $sheet->setCellValue("A1", "title");
                $sheet->setCellValue("B1", "description");
                $sheet->setCellValue("C1", "city");
                $sheet->setCellValue("D1", "district");
                $sheet->setCellValue("E1", "date");
                $sheet->setCellValue("F1", "time");
                $sheet->setCellValue("G1", "price");
                $sheet->setCellValue("H1", "ads_owner");
                $sheet->setCellValue("I1", "token");

                // store into Excel
                General::writeSheet($filePath, $fileName, 'Xls', $spreadsheet);

        }
        else {
                // update the file
                $spreadSheet = General::getSheet($filePath . $fileName, "Xls") ?? null;
                $activeSheet = $spreadSheet->getActiveSheet();

                $tokens = $this->getToken($filePath , $fileName); // list xls file tokens

                foreach ($info as $adsInfo) {

                    if(!in_array($adsInfo['token'], $tokens)) {
                        $lastRow = $activeSheet->getHighestRow();

                        $activeSheet->setCellValue("A" . $lastRow + 1, $adsInfo['title']);
                        $activeSheet->setCellValue("B" . $lastRow + 1, $adsInfo['description']);
                        $activeSheet->setCellValue("C" . $lastRow + 1, $adsInfo['city']);
                        $activeSheet->setCellValue("D" . $lastRow + 1, $adsInfo['district']);
                        $activeSheet->setCellValue("E" . $lastRow + 1, $adsInfo['date']);
                        $activeSheet->setCellValue("F" . $lastRow + 1, $adsInfo['time']);
                        $activeSheet->setCellValue("G" . $lastRow + 1, $adsInfo['price']);
                        $activeSheet->setCellValue("H" . $lastRow + 1, $adsInfo['ads_owner']);
                        $activeSheet->setCellValue("I" . $lastRow + 1, $adsInfo['token']);

                    }
                }


                // store into Excel
                General::writeSheet($filePath, $fileName, 'Xls', $spreadSheet);

        }

        if (empty($info)) {
                var_dump("end!");
                $status = false; // end process
        }
        else {
                var_dump("next!");
                $status = true; // next process
        }

        return $status;
    }

    /**
     * list xls file tokens
     * @param string $filePath
     * @param string $fileName
     * @return array
     */
    public function getToken(string $filePath ,string $fileName)
    {
        $sheetArray = General::getSheetArray($filePath . $fileName, "Xls") ?? null;
        $tokens = [];
        foreach ($sheetArray as $xlsInfo){
            $tokens[] = $xlsInfo[8];
        }

        return $tokens;
    }

    /**
     * @param $rsp
     * @return void
     */
    protected function parseExportMajor($filterPrice, $categoryName,  $rsp, $filterDate=null):bool
    {

        // set current date for file name
        $date = explode("/",currentDate());
        $dateShamsi = gregorian_to_jalali($date[0],$date[1], $date[2]);
        $filePath = $dateShamsi[0]."/".$dateShamsi[1]."/";

        $filePath = sprintf(self::FILE_PATH, self::SCRIPT_NAME . $categoryName . $filePath);

        $fileName = $dateShamsi[2] . ".xls";

        $json = json_decode($rsp);
        $data = $json->list_widgets ?? [];
        if(!empty($data)) {
            $info = [];
            // loop the ads data
            foreach ($data as $adsList) {

                if ($adsList->widget_type === "POST_ROW") {

                    if (isset($adsList->data->red_text)) {
                        $ads_owner = "shop";
                    } else {
                        $ads_owner = "people";
                    }

                    $date = explode("T", $adsList->action_log->server_side_info->info->sort_date)[0];
                    $time = explode("T", $adsList->action_log->server_side_info->info->sort_date)[1];
                    $date = explode("-", $date);
                    $dateShamsi = gregorian_to_jalali($date[0], $date[1], $date[2]);
                    $dateShamsi = $dateShamsi[0] . "/" . $dateShamsi[1] . "/" . $dateShamsi[2];

                    $price = $adsList->data->middle_description_text;
                    $unicode = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
                    $english = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
                    $price = str_replace($unicode, $english, trim($price));
                    $price = preg_replace('/[^0-9]/', '', $price);

                    if ($price !== str_repeat('1', strlen($price)) && $price < $filterPrice) {
                        if ($dateShamsi >= $filterDate) {

                            $info[] = [
                                "title" => $adsList->data->action->payload->web_info->title,
                                "description" => $adsList->data->action->payload->web_info->title,
                                "city" => $adsList->data->action->payload->web_info->city_persian,
                                "district" => $adsList->data->action->payload->web_info->district_persian,
                                "date" => $dateShamsi,
                                "time"=>$time,
                                "price" => $price,
                                "ads_owner" => $ads_owner,
                                'token' => $adsList->data->action->payload->token
                            ];
                        }
                    }
                }
            }

            // insert into the file
            if (!is_file($filePath . $fileName)) {

                // create an instance of Spreadsheet()
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // set columns titles
                $sheet->setCellValue("A1", "title");
                $sheet->setCellValue("B1", "description");
                $sheet->setCellValue("C1", "city");
                $sheet->setCellValue("D1", "district");
                $sheet->setCellValue("E1", "date");
                $sheet->setCellValue("F1", "time");
                $sheet->setCellValue("G1", "price");
                $sheet->setCellValue("H1", "ads_owner");
                $sheet->setCellValue("I1", "token");

                // store into Excel
                General::writeSheet($filePath, $fileName, 'Xls', $spreadsheet);

            } else {
                // update the file
                $spreadSheet = General::getSheet($filePath . $fileName, "Xls") ?? null;
                $activeSheet = $spreadSheet->getActiveSheet();

                foreach ($info as $adsInfo) {
                    $lastRow = $activeSheet->getHighestRow();

                    $activeSheet->setCellValue("A" . $lastRow + 1, $adsInfo['title']);
                    $activeSheet->setCellValue("B" . $lastRow + 1, $adsInfo['description']);
                    $activeSheet->setCellValue("C" . $lastRow + 1, $adsInfo['city']);
                    $activeSheet->setCellValue("D" . $lastRow + 1, $adsInfo['district']);
                    $activeSheet->setCellValue("E" . $lastRow + 1, $adsInfo['date']);
                    $activeSheet->setCellValue("F" . $lastRow + 1, $adsInfo['time']);
                    $activeSheet->setCellValue("G" . $lastRow + 1, $adsInfo['price']);
                    $activeSheet->setCellValue("H" . $lastRow + 1, $adsInfo['ads_owner']);
                    $activeSheet->setCellValue("I" . $lastRow + 1, $adsInfo['token']);

                }
                // store into Excel
                General::writeSheet($filePath, $fileName, 'Xls', $spreadSheet);
            }

            //var_dump($info);
            if (empty($info)) {
                var_dump("end!");
                $status = false; // end process
            } else {
                var_dump("next!");
                $status = true; // next process
            }
        }else{
            var_dump("end scrolling!");
            $status = false;
        }

        return $status;
    }


    /**
     * @param $body
     * @return void
     * @throws \Exception
     */
    public function checkResponseErrors($body)
    {
        $json = json_decode($body);
        if(isset($json->error_code)){
            throw new \Exception($json->message->title);
        }
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

    public function asyncRequest($promises)
    {
        return $this->asyncSubmitRequest($promises);
    }
}