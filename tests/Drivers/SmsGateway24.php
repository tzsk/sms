<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Tests\Mocks\Drivers\MockSmsGateway24;
use Tzsk\Sms\Tests\TestCase;

class SmsGateway24 extends TestCase
{
    use DriverCommon;

    protected function getDriver()
    {
        return new MockSmsGateway24();
    }
}
