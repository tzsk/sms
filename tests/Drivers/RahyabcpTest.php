<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Tests\Mocks\Drivers\MockRahyabcp;
use Tzsk\Sms\Tests\TestCase;

class RahyabcpTest extends TestCase
{
    use DriverCommon;

    protected function getDriver()
    {
        return new MockRahyabcp();
    }
}
