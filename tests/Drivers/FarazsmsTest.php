<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Tests\Mocks\Drivers\MockFarazsms;
use Tzsk\Sms\Tests\TestCase;

class FarazsmsTest extends TestCase
{
    use DriverCommon;

    protected function getDriver()
    {
        return new MockFarazsms();
    }
}
