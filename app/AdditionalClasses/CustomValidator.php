<?php


namespace App\AdditionalClasses;


use Illuminate\Database\Eloquent\Model;

class CustomValidator
{
    /**
     * @param $phone_number
     * @return bool
     */
    public static function mobile_validator($phone_number)
    {
        $pattern = "/^(?:\+?98|0)[9]{1}[0-9]{9}$/";
        if(preg_match($pattern, Date::convertPersianNumToEnglish($phone_number))) {
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public static function getPreviousRouteName()
    {
        return app('router')->getRoutes(url()->previous())->match(app('request')->create(url()->previous()))->getName();
    }

    /**
     * remove Url parameter
     *
     * @param string $url
     * @param string $parameter
     * @return string
     */
    public static function removeUrlParameter(string $url, string $parameter) {
        list($urlpart, $qspart) = array_pad(explode('?', $url), 2, '');
        parse_str($qspart, $qsvars);
        unset($qsvars[$parameter]);
        $newqs = http_build_query($qsvars);
        return $urlpart . '?' . $newqs;
    }
}
