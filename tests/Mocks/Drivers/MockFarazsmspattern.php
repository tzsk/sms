<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

use Tzsk\Sms\Drivers\Farazsmspattern;

class MockFarazsmspattern extends Farazsmspattern
{
    use MockCommon;

    public function __construct(array $settings = [])
    {
        parent::__construct(config('sms.drivers.farazsmspattern'));
    }
}
