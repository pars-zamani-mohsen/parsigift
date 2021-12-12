<?php namespace App\AdditionalClasses;

use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;

class Date
{
    /**
     * Date format Validation
     *
     * @param $value 1400/03/25
     * @return bool
     */
    public static function dateValidate($value)
    {
        return ($value && preg_match_all('/^\d{4}\/(0?[1-9]|1[012])\/(0?[1-9]|[12][0-9]|3[01])$/i', $value));
    }

    /**
     * @param $timestamp_start
     * @param $timestamp_end
     * @param string $type
     * @return \DateInterval|false
     * @throws \Exception
     */
    public static function dateDiff($timestamp_start, $timestamp_end, $type = 'datetime')
    {
        if ($type == 'datetime') $format = 'Y-m-d H:i:s'; else $format = 'Y-m-d';
        $earlier = new DateTime(date($format, $timestamp_start));
        $later = new DateTime(date($format, $timestamp_end));

        return $later->diff($earlier);
    }

    /**
     * @param $date
     * @return int
     * give the PersianDate 1367/04/04 to him and get timestamp!
     */
    public static function shamsiDateTimeToTimestamp($date)
    {
        if ($date) {
            $PersianDatetimeArray = explode(' ', Date::convertPersianNumToEnglish($date));
            $PersianDateArray = explode('/', Date::convertPersianNumToEnglish($PersianDatetimeArray[0]));
            $MiladiDateArray = CalendarUtils::toGregorian($PersianDateArray[0], $PersianDateArray[1], $PersianDateArray[2]);
            $MiladiDateString = implode($MiladiDateArray, '-') . ' ' . $PersianDatetimeArray[1];
            $timestamp = strtotime($MiladiDateString);
        } else {
            $timestamp = time();
        }

        return $timestamp;
    }

    /**
     * @param $date
     * @return int
     * give the PersianDate 1367/04/04 to him and get timestamp!
     */
    public static function shamsiToTimestamp($date)
    {
        if ($date) {
            $PersianDateArray = explode('/', Date::convertPersianNumToEnglish(str_replace('-', '/', $date)));
            $MiladiDateArray = CalendarUtils::toGregorian($PersianDateArray[0], $PersianDateArray[1], $PersianDateArray[2]);
            $MiladiDateString = implode($MiladiDateArray, '-');
            $timestamp = strtotime($MiladiDateString);
        } else {
            $timestamp = time();
        }

        return $timestamp;
    }

    public static function timestampToShamsi($timestamp)
    {
        if (!$timestamp) $timestamp = time();
        $result = Jalalian::forge($timestamp)->format('%Y/%m/%d');
        return Date::convertEnglishNumToPersian($result);
    }

    public static function timestampToShamsiDatetime($timestamp)
    {
        if (!$timestamp) $timestamp = time();
        $result = Jalalian::forge($timestamp)->format('%Y/%m/%d H:i:s');
        return Date::convertEnglishNumToPersian($result);
    }

    public static function timestampToShamsiDatetimeEng($timestamp)
    {
        if (!$timestamp) $timestamp = time();
        $result = Jalalian::forge($timestamp)->format('%Y/%m/%d H:i:s');
        return Date::convertPersianNumToEnglish($result);
    }

    public static function timestampToShamsiEng($timestamp)
    {
        if (!$timestamp) $timestamp = time();
        $result = Jalalian::forge($timestamp)->format('%Y/%m/%d');
        return Date::convertPersianNumToEnglish($result);
    }

    public static function timestampToShamsiWithDay($timestamp)
    {
        if (!$timestamp) $timestamp = time();
        $result = Jalalian::forge($timestamp)->format('l %Y/%m/%d');
        return Date::convertEnglishNumToPersian($result);
    }

    public static function timestampToShamsiWithDay_andNameOfMonth($timestamp)
    {
        if (!$timestamp) $timestamp = time();
        $result = Jalalian::forge($timestamp)->format('l %d F %Y');
        return Date::convertEnglishNumToPersian($result);
    }

    public static function timestampToShamsiWithDay_andNameOfMonth_andTime($timestamp)
    {
        if (!$timestamp) $timestamp = time();
        $result = Jalalian::forge($timestamp)->format('%A, %d %B %Y' . ' ساعت ' . ' H:i ');
        return Date::convertEnglishNumToPersian($result);
    }

    public static function convertEnglishNumToPersian($EnglishNumbers)
    {
        $farsi_array = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹");
        $english_array = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        return str_replace($english_array, $farsi_array, $EnglishNumbers);
    }

    public static function convertPersianNumToEnglish($PersianNumbers)
    {
        $farsi_array = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹");
        $english_array = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        return str_replace($farsi_array, $english_array, $PersianNumbers);
    }
}
