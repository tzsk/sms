<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

use Tzsk\Sms\Drivers\Melipayamakpattern;

class MockMelipayamakpattern extends Melipayamakpattern
{
    use MockCommon;

    public function __construct(array $settings = [])
    {
        parent::__construct(config('sms.drivers.melipayamakpattern'));
    }
}
