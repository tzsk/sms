<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

use Tzsk\Sms\Drivers\Farazsms;

class MockFarazsms extends Farazsms
{
    use MockCommon;

    public function __construct()
    {
        parent::__construct(config('sms.drivers.farazsms'));
    }
}
