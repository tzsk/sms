<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Tests\Mocks\Drivers\MockGhasedak;
use Tzsk\Sms\Tests\TestCase;

class GhasedakTest extends TestCase
{
    use DriverCommon;

    protected function getDriver()
    {
        return new MockGhasedak();
    }
}
