<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Tests\Mocks\Drivers\MockSabaPayamak;
use Tzsk\Sms\Tests\TestCase;

class SabaPayamakTest extends TestCase
{
    use DriverCommon;

    protected function getDriver()
    {
        return new MockSabaPayamak();
    }
}
