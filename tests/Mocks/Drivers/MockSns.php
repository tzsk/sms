<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

use Tzsk\Sms\Drivers\Sns;

class MockSns extends Sns
{
    use MockCommon;

    public function __construct()
    {
        parent::__construct(config('sms.drivers.sns'));
    }
}
