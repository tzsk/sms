<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

use Tzsk\Sms\Drivers\Tsms;

class MockTsms extends Tsms
{
    use MockCommon;

    public function __construct()
    {
        parent::__construct(config('sms.drivers.tsms'));
    }
}
