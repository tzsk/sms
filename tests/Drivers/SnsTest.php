<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Tests\Mocks\Drivers\MockSns;
use Tzsk\Sms\Tests\TestCase;

class SnsTest extends TestCase
{
    use DriverCommon;

    protected function getDriver()
    {
        return new MockSns();
    }
}
