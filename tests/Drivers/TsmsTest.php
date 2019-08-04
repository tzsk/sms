<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Tests\TestCase;
use Tzsk\Sms\Tests\Mocks\Drivers\MockTsms;

class TsmsTest extends TestCase
{
    use DriverCommon;

    protected function getDriver()
    {
        return new MockTsms();
    }
}
