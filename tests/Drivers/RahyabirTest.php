<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Tests\Mocks\Drivers\MockRahyabir;
use Tzsk\Sms\Tests\TestCase;

class RahyabirTest extends TestCase
{
    use DriverCommon;

    protected function getDriver()
    {
        return new MockRahyabir();
    }
}
