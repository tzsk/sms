<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Tests\Mocks\Drivers\MockTwilio;
use Tzsk\Sms\Tests\TestCase;

class TwilioTest extends TestCase
{
    use DriverCommon;

    protected function getDriver()
    {
        return new MockTwilio();
    }
}
