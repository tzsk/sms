<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

use Tzsk\Sms\Drivers\SmsGatewayMe;

class MockSmsGatewayMe extends SmsGatewayMe
{
    use MockCommon;

    public function __construct(array $settings = [])
    {
        parent::__construct(config('sms.drivers.smsgatewayme'));
    }
}
