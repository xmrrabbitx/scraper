<?php

namespace Scraper\Trader\divar;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Scraper\Trader\core\apiRequest;
use Scraper\Trader\core\General;
use function Scraper\Trader\core\utilities\gregorian_to_jalali;
use function Scraper\Trader\core\utilities\random_user_agent;
use function Scraper\Trader\core\utilities\currentDate;

class divarApi extends apiRequest
{
    protected array $session;

    const SEARCH_CATEGORIES = "https://api.divar.ir/v8/postlist/w/search";

    const FILE_PATH = "./src/xls/%s";

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
     * @return void
     */
    public function cloth(string $cityName=null, int $layerPage=0, int $filterPrice=10000000):void
    {
        try {

            $date = currentDate();
            $date = explode("/", $date);
            $dateShamsi = gregorian_to_jalali($date[0], $date[1], $date[2]);
            $dateShamsi = $dateShamsi[0] . "/" . $dateShamsi[1] . "/" . $dateShamsi[2];
            $this->session['currentDate'] = $dateShamsi;

            $data = [
                    "city_ids"=>[$cityName ?? self::CITY_CODES['tehran']],
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
                $status = $this->parseExport($filterPrice, $rsp);

                sleep(5);
                $layerPage++;

                // next layer date ads
                if($status){
                    $this->cloth($cityName, $layerPage);
                }

        }catch (\Exception $error){
            //var_dump($error);
        }

    }

    /**
     * @param $rsp
     * @return void
     */
    protected function parseExport($filterPrice, $rsp):bool
    {

        // set current date for file name
        $date = explode("/",currentDate());
        $dateShamsi = gregorian_to_jalali($date[0],$date[1], $date[2]);
        $filePath = $dateShamsi[0]."/".$dateShamsi[1]."/";

        $filePath = sprintf(self::FILE_PATH, $filePath);

        $fileName = $dateShamsi[2] . ".xls";

        // loop the ads data
        $json = json_decode($rsp);
        $data = $json->list_widgets;
        $info = [];
        foreach ($data as $adsList){

            if($adsList->widget_type === "POST_ROW") {

                if (isset($adsList->data->red_text)) {
                    $type = "shop";
                } else {
                    $type = "people";
                }

                $date = explode("T", $adsList->action_log->server_side_info->info->sort_date)[0];
                $date = explode("-", $date);
                $dateShamsi = gregorian_to_jalali($date[0], $date[1], $date[2]);
                $dateShamsi = $dateShamsi[0] . "/" . $dateShamsi[1] . "/" . $dateShamsi[2];

                $price = $adsList->data->middle_description_text;
                $unicode = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
                $english = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
                $price = str_replace($unicode, $english, trim($price));
                $price = preg_replace('/[^0-9]/', '', $price);

                if($price !== str_repeat('1', strlen($price)) && $price < $filterPrice) {

                    $info[] = [
                        "title" => $adsList->data->action->payload->web_info->title,
                        "city" => $adsList->data->action->payload->web_info->city_persian,
                        "district" => $adsList->data->action->payload->web_info->district_persian,
                        "date" => $dateShamsi,
                        "price" => $price,
                        "type" => $type,
                        'token' => $adsList->data->action->payload->token
                    ];
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
                $sheet->setCellValue("B1", "city");
                $sheet->setCellValue("C1", "district");
                $sheet->setCellValue("D1", "date");
                $sheet->setCellValue("E1", "price");
                $sheet->setCellValue("F1", "type");
                $sheet->setCellValue("G1", "token");

                // store into Excel
                General::writeSheet($filePath, $fileName, 'Xls', $spreadsheet);

        }
        else {
                // update the file
                $spreadSheet = General::getSheet($filePath . $fileName, "Xls") ?? null;
                $activeSheet = $spreadSheet->getActiveSheet();

                foreach ($info as $adsInfo) {
                    $lastRow = $activeSheet->getHighestRow();

                    $activeSheet->setCellValue("A" . $lastRow + 1, $adsInfo['title']);
                    $activeSheet->setCellValue("B" . $lastRow + 1, $adsInfo['city']);
                    $activeSheet->setCellValue("C" . $lastRow + 1, $adsInfo['district']);
                    $activeSheet->setCellValue("D" . $lastRow + 1, $adsInfo['date']);
                    $activeSheet->setCellValue("E" . $lastRow + 1, $adsInfo['price']);
                    $activeSheet->setCellValue("F" . $lastRow + 1, $adsInfo['type']);
                    $activeSheet->setCellValue("F" . $lastRow + 1, $adsInfo['token']);

                }
                // store into Excel
                General::writeSheet($filePath, $fileName, 'Xls', $spreadSheet);
        }

        // check if day is ended
        $date = explode("/",currentDate());
        $currentDate = gregorian_to_jalali($date[0],$date[1], $date[2]);
        $currentDateShamsi = $currentDate[0] . "/" . $currentDate[1] . "/" . $currentDate[2];
        $endDate = end($info)['date'];
        if($endDate !== $currentDateShamsi){
            var_dump("end!");
            $status = false; // end process
        }
        else{
            var_dump("next!");
            $status = true; // next process
        }

        return $status;
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