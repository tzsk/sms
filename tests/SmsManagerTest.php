<?php

namespace Tzsk\Sms\Tests;

use Tzsk\Sms\Abstracts\Driver;
use Tzsk\Sms\Drivers\Textlocal;
use Tzsk\Sms\Tests\Mocks\MockSmsManager;

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

    public function test_it_has_send_method()
    {
        $response = (new MockSmsManager())->via('bar')
            ->send('Example', function ($message) {
                $message->to(['1234567890']);
            });
        $this->assertEquals('foo', $response);
    }
}
