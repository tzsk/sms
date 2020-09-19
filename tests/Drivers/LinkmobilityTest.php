<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Tests\Mocks\Drivers\MockLinkmobility;
use Tzsk\Sms\Tests\TestCase;

class LinkmobilityTest extends TestCase
{
    use DriverCommon;

    protected function getDriver()
    {
        return new MockLinkmobility();
    }
}
