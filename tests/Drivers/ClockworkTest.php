<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Tests\Mocks\Drivers\MockClockwork;
use Tzsk\Sms\Tests\TestCase;

class ClockworkTest extends TestCase
{
    use DriverCommon;

    protected function getDriver()
    {
        return new MockClockwork();
    }
}
