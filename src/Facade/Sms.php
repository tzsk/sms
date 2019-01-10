<?php

namespace Tzsk\Sms\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Class Sms
 * @package Tzsk\Sms\Facade
 * @see \Tzsk\Sms\SmsManager
 */
class Sms extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'tzsk-sms';
    }
}
