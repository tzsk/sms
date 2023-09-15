<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

use Tzsk\Sms\Drivers\SmsApi;

class MockSmsApi extends SmsApi
{
    use MockCommon;

    public function __construct(array $settings = [])
    {
        parent::__construct(config('sms.drivers.smsapi'));
    }
}
