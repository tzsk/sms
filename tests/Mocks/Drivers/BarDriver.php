<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

use Tzsk\Sms\Abstracts\Driver;

class BarDriver extends Driver
{
    use MockCommon;

    protected $settings;

    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    public function send()
    {
        return $this;
    }
}
