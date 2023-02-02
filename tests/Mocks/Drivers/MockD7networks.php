<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

use Tzsk\Sms\Drivers\D7networks;

class MockD7networks extends D7networks
{
    use MockCommon;

    public function __construct()
    {
        parent::__construct(config('sms.drivers.d7networks'));
    }
}
