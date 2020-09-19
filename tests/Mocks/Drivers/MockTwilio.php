<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

use Tzsk\Sms\Drivers\Twilio;

class MockTwilio extends Twilio
{
    use MockCommon;

    public function __construct(array $settings = [])
    {
        parent::__construct(config('sms.drivers.twilio'));
    }
}
