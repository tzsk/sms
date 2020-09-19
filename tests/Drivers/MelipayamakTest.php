<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Tests\Mocks\Drivers\MockMelipayamak;
use Tzsk\Sms\Tests\TestCase;

class MelipayamakTest extends TestCase
{
    use DriverCommon;

    protected function getDriver()
    {
        return new MockMelipayamak();
    }
}
