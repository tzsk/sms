<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Tests\Mocks\Drivers\MockLSim;
use Tzsk\Sms\Tests\Mocks\Drivers\MockSabaPayamak;
use Tzsk\Sms\Tests\TestCase;

class LSimTest extends TestCase
{
    use DriverCommon;

    protected function getDriver(): MockLSim
    {
        return new MockLSim();
    }
}
