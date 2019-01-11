<?php

namespace Tzsk\Sms\Tests\Mocks;

use Tzsk\Sms\SmsManager;

class MockSmsManager extends SmsManager
{
    public function __construct()
    {
        parent::__construct(config('sms'));
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function driverInstance()
    {
        return $this->getDriverInstance();
    }
}
