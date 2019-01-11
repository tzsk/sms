<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

use Tzsk\Sms\Drivers\Smsir;

class MockSmsir extends Smsir
{
    use MockCommon;

    public function __construct()
    {
        parent::__construct(config('sms.drivers.smsir'));
    }
}
