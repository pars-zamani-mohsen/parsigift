<?php

namespace App;

use App\Jobs\SendMail;
use App\Jobs\SendSimpleSMS;
use Kavenegar\KavenegarApi;
use App\AdditionalClasses\Date;
use App\Jobs\SendArraySimpleSMS;
use App\Jobs\VerifyLookupV2SendSMS;
use Illuminate\Support\Facades\Auth;
use Kavenegar\Exceptions\ApiException;
use Illuminate\Database\Eloquent\Model;
use Kavenegar\Exceptions\HttpException;
use App\AdditionalClasses\CustomKavenegarApi;

class Message extends Model
{
    /**
     * @return string
     */
    public static function getSmsSenderNumber()
    {
        return '100007744';
    }

    /**
     * @param string $sender
     * @param array $receptor
     * @param string $message
     * @param null $date
     * @param null $type
     * @param null $localid
     */
    public static function send_simple_sms(string $sender, array $receptor, string $message, $date = null, $type = null, $localid = null)
    {
        try {
            SendSimpleSMS::dispatch($sender, $receptor, $message, $date, $type, $localid);
        } catch (ApiException $e) {
        } catch (\Kavenegar\Exceptions\HttpException $e) {
        }
    }

    /**
     * @param array $message
     * @param array $receptor
     * @param string $sender
     */
    public static function send_array_simple_sms(array $sender, array $receptor, array $message, $date = null, $type = null, $localid = null)
    {
        try {
            SendArraySimpleSMS::dispatch($sender, $receptor, $message, $date, $type, $localid);
        } catch (ApiException $e) {
        } catch (\Kavenegar\Exceptions\HttpException $e) {
        }
    }
}
