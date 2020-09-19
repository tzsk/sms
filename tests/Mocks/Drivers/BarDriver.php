<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

use Tzsk\Sms\Contracts\Driver;

class BarDriver extends Driver
{
    use MockCommon;

    protected $settings;

    public function __construct(array $settings = [])
    {
        $this->settings = $settings;
    }

    public function send()
    {
        return $this;
    }
}
