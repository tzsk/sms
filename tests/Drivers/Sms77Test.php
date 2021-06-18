<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Tests\Mocks\Drivers\MockSms77;
use Tzsk\Sms\Tests\TestCase;

class Sms77Test extends TestCase
{
    use DriverCommon;

    protected function getDriver()
    {
        return new MockSms77();
    }
}
