<?php

/**
 * Created by PhpStorm.
 * User: Cachu
 * Date: 02/may/2017
 * Time: 05:25 PM
 */

namespace classes;

use Exception;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class paypal
{
    //live
    private static $mode;
    private
        $ClientID,
        $ClientSecret;

    function __construct()
    {
        $this->{self::$mode}();
    }

    static function setMode($mode)
    {
        self::$mode = $mode;
    }

    function sandbox()
    {
        /**
         * info@grupocamrey.com
         *
         * $ClientID AcSSFaeWzQE9I81TDVbflfpEqLFTYDmXtcKMtheDrJGVpgG8XOMBdnVEmyrt0AwqFxRTY-mrJRKsGWju
         * $ClientSecret EAG9GUBLWEAuxK70QXn9Y7GIkh7KBTj1PSKChODA7MS6jigi7zs_tLzH7t9t1foQpme902rV5DdwE-f-
         */

        /** Jose Luis */
        $this->ClientID = '';
        $this->ClientSecret = '';
    }

    function live()
    {
        /**
         * info@grupocamrey.com
         *
         * $ClientID AQ_3ywrjdm1Be3MaCnlV10iA6piaoC9z4U8uVeFmtttcSaaARH3aCMMl3Fn9e-0_zQJos0GYsfI_SP9-
         * $ClientSecret EJ1jB_N7-8b-ZFauKbZiXUGYKjYPybh2wA83J98oYnutJyHI4XK1NjhLU2uJhgugIGG27YBXlQwCgQ_p
         */

        /** Jose Luis */
        $this->ClientID = 'AXEWTnPPuuvE-3tNHTtC4eHgoU4YPvUUiK5qvxQHBVPcbCZdOgRFQXyB3Hxj9XUfHgXCJ1omNTahAI3E';
        $this->ClientSecret = 'EPQ4Aowdu21QI3mDQJMunHLMBTOpKon-JZZKoxQMTbjraswOd0iYOzPei_IA-Jo7l8qsuqbiqeHr4Whi';
    }

    static function getApiContext()
    {
        try {
            $paypal = new Paypal();
            $apiContext = new ApiContext(
                new OAuthTokenCredential(
                    $paypal->ClientID,        // ClientID
                    $paypal->ClientSecret     // ClientSecret
                )
            );
            $apiContext->setConfig(
                array(
                    'mode' => self::$mode
                )
            );
        } catch (Exception $ex) {

        }
        return $apiContext;
    }
}