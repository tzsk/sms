<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

use Tzsk\Sms\Contracts\Driver;

class BarDriver extends Driver
{
    use MockCommon;

    public function send()
    {
        return $this;
    }
}
