<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

use Tzsk\Sms\Drivers\Hamyarsms;

class MockHamyarsms extends Hamyarsms
{
    use MockCommon;

    public function __construct(array $settings = [])
    {
        parent::__construct(config('sms.drivers.hamyarsms'));
    }
}
