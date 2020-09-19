<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Tests\Mocks\Drivers\MockKavenegar;
use Tzsk\Sms\Tests\TestCase;

class KavenegarTest extends TestCase
{
    use DriverCommon;

    protected function getDriver()
    {
        return new MockKavenegar();
    }
}
