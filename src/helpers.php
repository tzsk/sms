<?php


if (!function_exists('sms')) {
    /**
     * Access SmsManager through helper.
     * @return \Tzsk\Sms\SmsManager
     */
    function sms()
    {
        return app('tzsk-sms');
    }
}
