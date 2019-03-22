<?php

namespace Tzsk\Sms\Tests;

class HelperTest extends TestCase
{
    public function test_helper_exists()
    {
        $this->assertTrue(function_exists('sms'));
    }

    public function test_helper_returns_correct_instance()
    {
        $sms = app('tzsk-sms');
        $this->assertTrue(sms() instanceof $sms);
    }
}
