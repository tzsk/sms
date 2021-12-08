<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

use Tzsk\Sms\Drivers\SabaPayamak;

class MockSabaPayamak extends SabaPayamak
{
    use MockCommon;

    public function __construct()
    {
        parent::__construct(config('sms.drivers.sabapayamak'));
    }
}
