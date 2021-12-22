<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

use Tzsk\Sms\Drivers\SmsGateway24;

class MockSmsGateway24 extends SmsGateway24
{
    use MockCommon;

    public function __construct(array $settings = [])
    {
        parent::__construct(config('sms.drivers.smsgateway24'));
    }
}
