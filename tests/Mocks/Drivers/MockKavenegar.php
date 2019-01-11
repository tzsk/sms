<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

use Tzsk\Sms\Drivers\Kavenegar;

class MockKavenegar extends Kavenegar
{
    use MockCommon;

    public function __construct()
    {
        parent::__construct(config('sms.drivers.kavenegar'));
    }
}
