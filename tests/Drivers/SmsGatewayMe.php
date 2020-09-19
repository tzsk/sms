<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Tests\Mocks\Drivers\MockSmsGatewayMe;
use Tzsk\Sms\Tests\TestCase;

class SmsGatewayMe extends TestCase
{
    use DriverCommon;

    protected function getDriver()
    {
        return new MockSmsGatewayMe();
    }
}
