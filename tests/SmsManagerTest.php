<?php

namespace Tzsk\Sms\Tests;

use Tzsk\Sms\Abstracts\Driver;
use Tzsk\Sms\Drivers\Textlocal;
use Tzsk\Sms\Tests\Mocks\MockSmsManager;
use Tzsk\Sms\Facade\Sms;
use Tzsk\Sms\Tests\Mocks\Drivers\BarDriver;

class SmsManagerTest extends TestCase
{
    public function test_it_has_default_driver()
    {
        $manager = new MockSmsManager();

        $this->assertArraySubset(config('sms'), $manager->getConfig());
        $this->assertEquals(config('sms.default'), $manager->getDriver());
        $this->assertArraySubset($manager->getSettings(), config('sms.drivers.'.$manager->getDriver()));
    }

    public function test_it_wont_accespt_wrong_driver()
    {
        $this->expectException(\Exception::class);
        $manager = (new MockSmsManager())->via('foo');
    }

    public function test_driver_can_be_changed()
    {
        $gateway = 'twilio';
        $manager = (new MockSmsManager())->via($gateway);

        $this->assertEquals($gateway, $manager->getDriver());
        $this->assertArraySubset($manager->getSettings(), config('sms.drivers.'.$gateway));
    }

    public function test_it_has_proper_driver_instance()
    {
        $manager = (new MockSmsManager());

        $this->assertInstanceOf(Driver::class, $manager->driverInstance());
    }

    public function test_can_call_directly()
    {
        $response = (new MockSmsManager())->via('bar')
            ->send('foo', function ($message) {
                $message->to(['baz']);
            });
        $this->assertInstanceOf(BarDriver::class, $response);
        $this->assertEquals('foo', $response->getBody());
        $this->assertContains('baz', $response->getRecipients());
    }

    public function test_can_call_from_facade()
    {
        $response = Sms::via('bar')->send('foo', function ($m) {
            $m->to('baz');
        });

        $this->assertInstanceOf(BarDriver::class, $response);
        $this->assertEquals('foo', $response->getBody());
        $this->assertContains('baz', $response->getRecipients());
    }
}
