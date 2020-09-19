<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

use Tzsk\Sms\Drivers\Clockwork;

class MockClockwork extends Clockwork
{
    use MockCommon;

    public function __construct(array $settings = [])
    {
        parent::__construct(config('sms.drivers.clockwork'));
    }
}
