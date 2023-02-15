<?php

if (! function_exists('sms')) {
    /**
     * Access SmsManager through helper.
     *
     * @return \Tzsk\Sms\Sms
     */
    function sms()
    {
        return app('tzsk-sms');
    }
}
