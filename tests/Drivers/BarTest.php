<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Tests\Mocks\Drivers\BarDriver;
use Tzsk\Sms\Tests\TestCase;

class BarTest extends TestCase
{
    use DriverCommon;

    protected function getDriver()
    {
        return new BarDriver(config('sms.drivers.bar'));
    }
}
