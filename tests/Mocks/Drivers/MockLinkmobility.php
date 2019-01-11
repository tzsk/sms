<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

use Tzsk\Sms\Drivers\Linkmobility;

class MockLinkmobility extends Linkmobility
{
    use MockCommon;

    public function __construct()
    {
        parent::__construct(config('sms.drivers.linkmobility'));
    }
}
