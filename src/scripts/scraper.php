<?php

namespace Scraper\Trader\scripts;

include "../../vendor/autoload.php";

// check if any data sent to page
$data = $_POST;
if(isset($data)){

    $className = "Scraper\\Trader\\" . $data['className'] . "\\". $data['className'] . 'Api';
    $class = new $className;

    if($data['type'] === 'simple') {

        call_user_func([$class, $data['functionName']]);

    }elseif($data['type'] === 'major') {

        $args = [
            "major",
            $data['cityName'],
            0,
            $data['date']
        ];
        call_user_func_array([$class, $data['functionName']], $args);
    }

}
