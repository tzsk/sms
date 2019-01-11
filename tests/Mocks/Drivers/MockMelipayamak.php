<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

use Tzsk\Sms\Drivers\Melipayamak;

class MockMelipayamak extends Melipayamak
{
    use MockCommon;

    public function __construct()
    {
        parent::__construct(config('sms.drivers.melipayamak'));
    }
}
