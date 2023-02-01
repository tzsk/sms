<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

use Tzsk\Sms\Drivers\Rahyabir;

class MockRahyabir extends Rahyabir
{
    use MockCommon;

    public function __construct()
    {
        parent::__construct(config('sms.drivers.rahyabir'));
    }
}
