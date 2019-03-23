<?php

namespace Tzsk\Sms\Tests;

class HelpersTest extends TestCase
{
    public function testHelperExists()
    {
        $this->assertTrue(function_exists('sms'));
    }

    public function testHelperReturnsCorrectInstance()
    {
        $sms = app('tzsk-sms');
        $this->assertTrue(sms() instanceof $sms);
    }
}
