<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Tests\Mocks\Drivers\MockFarazsmspattern;
use Tzsk\Sms\Tests\TestCase;

class FarazsmspatternTest extends TestCase
{
    use DriverCommon;

    protected function getDriver()
    {
        return new MockFarazsmspattern();
    }
}
