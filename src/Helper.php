<?php

use Tzsk\Sms\Sms;

if (! function_exists('sms')) {
    /**
     * Access SmsManager through helper.
     *
     * @return Sms
     */
    function sms()
    {
        return app('tzsk-sms');
    }
}
