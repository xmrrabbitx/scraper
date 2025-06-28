<?php

namespace Hopper\Scraper\core\utilities;

/**
 * Generates Iranian timestamp - Tehran, GMT+3:30
 *
 * @param bool $utc optional, set UTC timezone
 */
function ir_timestamp(bool $utc = false): int {
    $dt = new \DateTime(null, new \DateTimeZone('Asia/Tehran'));
    if ($utc) {
        $dt->setTimezone(new \DateTimeZone('UTC'));
    }

    return strtotime($dt->format('Y-m-d H:i:s'));
}

const MOBILE_DEVICES = [
    [
        'os'=> 'Android',
        'version_release'=> '5.1.1',
        'version_sdk'=> '22',
        'manufacturer'=> 'samsung',
        'model'=> 'SM-J320F',
        'build'=> 'LMY47V',
        'code_name'=> 'j3xlte',
        'board'=> 'SC9830I',
        'pda'=> 'J320FXXU0AQJ1',
        'abi'=> 'armeabi-v7a',
        'friendly_name'=> 'Galaxy J3 (2016)',
    ],
    [
        'os'=> 'Android',
        'version_release'=> '7.0',
        'version_sdk'=> '24',
        'manufacturer'=> 'samsung',
        'model'=> 'SM-G920F',
        'build'=> 'NRD90M',
        'code_name'=> 'zeroflte',
        'board'=> 'universal7420',
        'pda'=> 'G920FXXU6ESB1',
        'abi'=> 'armeabi-v7a',
        'friendly_name'=> 'Galaxy S6',
    ],
    [
        'os'=> 'Android',
        'version_release'=> '8.1.0',
        'version_sdk'=> '27',
        'manufacturer'=> 'samsung',
        'model'=> 'SM-T585',
        'build'=> 'M1AJQ',
        'code_name'=> 'gtaxllte',
        'board'=> 'universal7870',
        'pda'=> 'T585XXU4CRJ8',
        'abi'=> 'armeabi-v7a',
        'friendly_name'=> 'Galaxy Tab A (2016)',
    ],
    [
        'os'=> 'Android',
        'version_release'=> '9',
        'version_sdk'=> '28',
        'manufacturer'=> 'motorola',
        'model'=> 'moto g(6)',
        'build'=> 'PPSS29.118-15-11-9',
        'code_name'=> 'ali',
        'board'=> 'msm8953',
        'pda'=> '34527',
        'abi'=> 'armeabi',
        'friendly_name'=> 'moto g(6)',
    ],
    [
        'os'=> 'Android',
        'version_release'=> '10',
        'version_sdk'=> '29',
        'manufacturer'=> 'samsung',
        'model'=> 'SM-A305FN',
        'build'=> 'QP1A.190711.020',
        'code_name'=> 'a30',
        'board'=> 'exynos7904',
        'pda'=> 'A305FDDU4BTB3',
        'abi'=> 'armeabi',
        'friendly_name'=> 'Galaxy A30',
    ],
    [
        'os'=> 'Android',
        'version_release'=> '6.0.1',
        'version_sdk'=> '23',
        'manufacturer'=> 'xiaomi',
        'model'=> 'Redmi 3S',
        'build'=> 'MMB29M',
        'code_name'=> 'land',
        'board'=> 'msm8937',
        'pda'=> '7.9.8',
        'abi'=> 'arm64-v8a',
        'friendly_name'=> 'Redmi 3S',
    ],
    [
        'os'=> 'Android',
        'version_release'=> '7.1.1',
        'version_sdk'=> '25',
        'manufacturer'=> 'asus',
        'model'=> 'ASUS_Z01HD',
        'build'=> 'NMF26F',
        'code_name'=> 'ASUS_Z01H_1',
        'board'=> 'msm8953',
        'pda'=> 'WW_Z01H-WW_user_71.60.139.30_201',
        'abi'=> 'arm64-v8a',
        'friendly_name'=> 'ZenFone 3 Zoom (ZE553KL)',
    ],
    [
        'os'=> 'Android',
        'version_release'=> '8.1.0',
        'version_sdk'=> '27',
        'manufacturer'=> 'xiaomi',
        'model'=> 'Redmi 6',
        'build'=> 'O11019',
        'code_name'=> 'cereus',
        'board'=> '',
        'pda'=> 'V10.3.3.0.OCGMIXM',
        'abi'=> 'armeabi',
        'friendly_name'=> 'Redmi 6',
    ],
    [
        'os'=> 'Android',
        'version_release'=> '8.0.0',
        'version_sdk'=> '26',
        'manufacturer'=> 'samsung',
        'model'=> 'SM-S367VL',
        'build'=> 'R16NW',
        'code_name'=> 'j3topeltetfnvzw',
        'board'=> 'universal7885',
        'pda'=> 'S367VLUDU2ARI3',
        'abi'=> 'armeabi-v7a',
        'friendly_name'=> 'Galaxy J3 Orbit',
    ],
    [
        'os'=> 'Android',
        'version_release'=> '6.0.1',
        'version_sdk'=> '23',
        'manufacturer'=> 'samsung',
        'model'=> 'SM-G925I',
        'build'=> 'MMB29K',
        'code_name'=> 'zerolte',
        'board'=> 'universal7420',
        'pda'=> 'G925IDVS4ESC1',
        'abi'=> 'armeabi-v7a',
        'friendly_name'=> 'Galaxy S6 Edge',
    ],
];


/**
 * Returns device settings, randomly selected from `MOBILE_DEVICES`.
 */
function random_device(): array {
    return MOBILE_DEVICES[array_rand(MOBILE_DEVICES)];
}

const MOBILE_USER_AGENTS = [
    'Mozilla/5.0 (Linux; Android 5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.5735.60 Mobile Safari/537.36',
    'Mozilla/5.0 (Linux; Android 6; SM-A205U) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.5735.60 Mobile Safari/537.36',
    'Mozilla/5.0 (Linux; Android 7; SM-A102U) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.5735.60 Mobile Safari/537.36',
    'Mozilla/5.0 (Linux; Android 8; SM-G960U) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.5735.60 Mobile Safari/537.36',
    'Mozilla/5.0 (Linux; Android 9; SM-N960U) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.5735.60 Mobile Safari/537.36',
    'Mozilla/5.0 (Linux; Android 10; LM-Q720) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.5735.60 Mobile Safari/537.36',
    'Mozilla/5.0 (Linux; Android 11; LM-X420) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.5735.60 Mobile Safari/537.36',
    'Mozilla/5.0 (Linux; Android 13; LM-Q710(FGN)) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.5735.60 Mobile Safari/537.36',
    'Mozilla/5.0 (Android 7; Mobile; rv:68.0) Gecko/68.0 Firefox/114.0',
    'Mozilla/5.0 (Android 9; Mobile; LG-M255; rv:114.0) Gecko/114.0 Firefox/114.0',
];

const DESKTOP_USER_AGENTS = [
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36 Edg/113.0.1774.57',
   'Mozilla/5.0 (Windows NT 10.0; WOW64; Trident/7.0; rv:11.0) like Gecko',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36',
    'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36',
    'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:114.0) Gecko/20100101 Firefox/114.0',
];

/**
 * Returns a random user-agent.
 */
function random_user_agent(bool $mobileOnly = false): string {
    $uaList = $mobileOnly ? MOBILE_USER_AGENTS : array_merge(MOBILE_USER_AGENTS, DESKTOP_USER_AGENTS);
    return $uaList[array_rand($uaList)];
}

/**
 * return current date in gregory format
 * @return mixed
 */
function currentDate()
{
    return date('Y-m-d');
}

/**
 * @param string $date
 * @return array
 * @throws InvalidDataException
 */
function parseDate(string $date): array {
    if (!preg_match('/^(\d{4})\/(\d{2})\/(\d{2})/', $date, $match)) {
        throw new InvalidDataException('Invalid date format (expected YYYY/MM/DD)');
    }

    return [
        'year'=> $match[1],
        'month'=> $match[2],
        'day'=> $match[3],
    ];
}

/**
 * Converts Jalali to Gregorian dates
 */
function jalali_to_gregorian($jy, $jm, $jd) {
    if ($jy > 979) {
        $gy = 1600;
        $jy -= 979;
    } else {
        $gy = 621;
    }

    $days = (365 * $jy) + (((int)($jy / 33)) * 8) + ((int)((($jy % 33) + 3) / 4)) + 78 + $jd + (($jm < 7) ? ($jm - 1) * 31 : (($jm - 7) * 30) + 186);
    $gy += 400 * ((int)($days / 146097));
    $days %= 146097;
    if ($days > 36524) {
        $gy += 100 * ((int)(--$days / 36524));
        $days %= 36524;
        if ($days >= 365)
            $days++;
    }

    $gy += 4 * ((int)(($days) / 1461));
    $days %= 1461;
    $gy += (int)(($days - 1) / 365);
    if ($days > 365)
        $days = ($days - 1) % 365;
    $gd = $days + 1;

    foreach (array(0, 31, ((($gy % 4 == 0) and ($gy % 100 != 0)) or ($gy % 400 == 0)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31) as $gm => $v) {
        if ($gd <= $v)
            break;
        $gd -= $v;
    }

    return array($gy, $gm, $gd);
}

/**
 * Converts Gregorian to Jalali dates
 */
function gregorian_to_jalali($gy, $gm, $gd) {
    list($gy, $gm, $gd) = explode('_', ($gy . '_' . $gm . '_' . $gd));
    $g_d_m = array(0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334);
    $jy = ($gy <= 1600) ? 0 : 979;
    $gy -= ($gy <= 1600) ? 621 : 1600;
    $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
    $days = (365 * $gy) + ((int)(($gy2 + 3) / 4)) - ((int)(($gy2 + 99) / 100))
        + ((int)(($gy2 + 399) / 400)) - 80 + $gd + $g_d_m[$gm - 1];
    $jy += 33 * ((int)($days / 12053));
    $days %= 12053;
    $jy += 4 * ((int)($days / 1461));
    $days %= 1461;
    $jy += (int)(($days - 1) / 365);
    if ($days > 365)
        $days = ($days - 1) % 365;

    $jm = ($days < 186) ? 1 + (int)($days / 31) : 7 + (int)(($days - 186) / 30);
    $jd = 1 + (($days < 186) ? ($days % 31) : (($days - 186) % 30));

    return array($jy, $jm, $jd);
}

?>
