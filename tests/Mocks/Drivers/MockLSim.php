<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

use Tzsk\Sms\Drivers\LSim;

class MockLSim extends LSim
{
    use MockCommon;

    public function __construct()
    {
        parent::__construct(config('sms.drivers.lsim'));
    }
}
