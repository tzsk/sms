<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Tests\Mocks\Drivers\MockTsms;
use Tzsk\Sms\Tests\TestCase;

class TsmsTest extends TestCase
{
    use DriverCommon;

    protected function getDriver()
    {
        return new MockTsms();
    }
}
