<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Tests\Mocks\Drivers\MockSmsir;
use Tzsk\Sms\Tests\TestCase;

class SmsirTest extends TestCase
{
    use DriverCommon;

    protected function getDriver()
    {
        return new MockSmsir();
    }
}
