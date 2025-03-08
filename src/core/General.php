<?php

namespace Scraper\Trader\core;

use DateTime;
use IntlDateFormatter;
use PhpOffice\PhpSpreadsheet\IOFactory;

class General {
    /**
     * @param $filePath
     * @param $fileType
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public static function getSheetArray($filePath, $fileType)
    {
        $reader = IOFactory::createReader($fileType);
        $spreadsheet = $reader->load($filePath);

        // Get the first worksheet
        $worksheet = $spreadsheet->getActiveSheet();
        return $worksheet->toArray();
    }

    /**
     * @param $shamsiDate
     * @param $modification
     * @return false|string
     * @throws Exception
     */
    public static function modifyJalaliDate($shamsiDate, $modification)
    {
        // تاریخ ورودی به فرمت "1403/12/01"
        list($year, $month, $day) = explode('/', $shamsiDate);

        // ایجاد IntlDateFormatter برای تقویم شمسی
        $formatter = new IntlDateFormatter(
            'fa_IR@calendar=persian',
            IntlDateFormatter::FULL,
            IntlDateFormatter::NONE,
            'Asia/Tehran',
            IntlDateFormatter::TRADITIONAL
        );

        // تبدیل تاریخ شمسی به میلادی
        $formatter->setPattern('yyyy/MM/dd');
        $gregorianDate = $formatter->parse("$year/$month/$day");

        // بررسی اینکه آیا تاریخ معتبر است
        if ($gregorianDate === false) {
            throw new Exception("Invalid Jalali date: $shamsiDate");
        }

        // ایجاد شیء DateTime میلادی
        $dateTime = (new DateTime())->setTimestamp($gregorianDate);

        // اعمال تغییرات (مثلاً "-1 day" یا "+1 day")
        $dateTime->modify($modification);

        // تبدیل تاریخ جدید به تقویم شمسی
        $formatter->setPattern('yyyy/MM/dd');
        return $formatter->format($dateTime);
    }

}