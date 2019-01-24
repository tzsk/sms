<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

use Tzsk\Sms\Drivers\Nexmo;

class MockNexmo extends Nexmo
{
    use MockCommon;

    public function __construct()
    {
        parent::__construct(config('sms.drivers.nexmo'));
    }
}
