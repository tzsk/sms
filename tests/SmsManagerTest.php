<?php

namespace Tzsk\Sms\Tests;

use Tzsk\Sms\SmsManager;

class SmsManagerTest extends LaravelTestCase
{
    public function test_it_has_settings()
    {
        $manager = new MockSmsManager();

        $this->assertEquals(config('sms.default'), $manager->getDriver());
    }
}

class MockSmsManager extends SmsManager
{
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
}
