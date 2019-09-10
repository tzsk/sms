<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Tests\TestCase;
use Tzsk\Sms\Tests\Mocks\Drivers\MockFarazsms;

class FarazsmsTest extends TestCase
{
    use DriverCommon;

    protected function getDriver()
    {
        return new MockFarazsms();
    }
}
