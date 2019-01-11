<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

use Tzsk\Sms\Drivers\Textlocal;

class MockTextLocal extends Textlocal
{
    use MockCommon;

    public function __construct()
    {
        parent::__construct(config('sms.drivers.textlocal'));
    }
}
