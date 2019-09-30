<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Tests\TestCase;
use Tzsk\Sms\Tests\Mocks\Drivers\MockSmsGatewayMe;

class SmsGatewayMe extends TestCase
{
    use DriverCommon;

    protected function getDriver()
    {
        return new MockSmsGatewayMe();
    }
}
