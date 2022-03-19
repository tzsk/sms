<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Tests\Mocks\Drivers\MockMelipayamakpattern;
use Tzsk\Sms\Tests\TestCase;

class MelipayamakpatternTest extends TestCase
{
    use DriverCommon;

    protected function getDriver()
    {
        return new MockMelipayamakpattern();
    }
}
