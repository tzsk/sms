<?php

namespace Tzsk\Sms\Tests\Drivers;

trait DriverCommon
{
    public function test_it_has_recipients()
    {
        $driver = $this->getDriver();

        $driver->to(['foo']);
        $driver->to('bar');

        $this->assertContains('foo', $driver->getRecipients());
        $this->assertContains('bar', $driver->getRecipients());
    }

    public function test_it_has_body()
    {
        $driver = $this->getDriver();

        $text = 'foo';
        $driver->message($text);

        $this->assertEquals($text, $driver->getBody());
    }
}
