<?php

namespace App\AdditionalClasses;

use Kavenegar\KavenegarApi;

class CustomKavenegarApi extends KavenegarApi
{
    /**
     * @param $receptor
     * @param $template
     * @param null $type
     * @param $token
     * @param $token2
     * @param $token3
     * @param $token10
     * @return mixed
     */
    public function VerifyLookupV2($receptor, $template, $type = null, $token, $token2, $token3, $token10)
    {
        $path = $this->get_path("lookup", "verify");
        $params = array(
            "template" => $template,
            "receptor" => $receptor,
            "token" => $token,
            "token2" => $token2,
            "token3" => $token3,
            "token10" => $token10,
            "type" => $type
        );
        return $this->execute($path, $params);
    }

    /**
     * @param $sender
     * @param array $receptor
     * @param $message
     * @param null $date
     * @param null $type
     * @param null $localid
     * @return mixed
     */
    public function VerifyLookupV2Simple($sender, array $receptor, $message, $date = null, $type = null, $localid = null)
    {
        return $this->Send($sender, $receptor, $message, $date, $type, $localid);
    }

    /**
     * @param array $sender
     * @param array $receptor
     * @param array $message
     * @param null $date
     * @param null $type
     * @param null $localid
     * @return mixed
     */
    public function VerifyLookupV2ArraySimple(array $sender, array $receptor, array $message, $date = null, $type = null, $localid = null)
    {
        return $this->SendArray($sender, $receptor, $message, $date, $type, $localid);
    }
}

?>