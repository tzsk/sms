<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Tests\Mocks\Drivers\MockHamyarsms;
use Tzsk\Sms\Tests\TestCase;

class HamyarsmsTest extends TestCase
{
    use DriverCommon;

    protected function getDriver()
    {
        return new MockHamyarsms();
    }
}
