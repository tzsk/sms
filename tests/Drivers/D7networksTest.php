<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Tests\Mocks\Drivers\MockD7networks;
use Tzsk\Sms\Tests\TestCase;

class D7networksTest extends TestCase
{
    use DriverCommon;

    protected function getDriver()
    {
        return new MockD7networks();
    }
}
