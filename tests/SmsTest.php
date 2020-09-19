<?php

namespace Tzsk\Sms\Tests;

use Tzsk\Sms\Builder;
use Tzsk\Sms\Facades\Sms;
use Tzsk\Sms\Tests\Mocks\Drivers\BarDriver;
use Tzsk\Sms\Tests\Mocks\MockSmsManager;

class SmsTest extends TestCase
{
    public function test_it_has_default_driver()
    {
        $manager = new MockSmsManager();

        $this->assertSameSize(config('sms'), $manager->getConfig());
        $this->assertEquals(config('sms.default'), $manager->getDriver());
        $this->assertSameSize($manager->getSettings(), config('sms.drivers.'.$manager->getDriver()));
    }

    public function test_it_wont_accept_wrong_driver()
    {
        $this->expectException(\Exception::class);
        $manager = (new MockSmsManager())->via('foo');
    }

    public function test_driver_can_be_changed()
    {
        $gateway = 'twilio';
        $manager = (new MockSmsManager())->via($gateway);

        $this->assertEquals($gateway, $manager->getDriver());
        $this->assertSameSize($manager->getSettings(), config('sms.drivers.'.$gateway));
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

    public function test_can_be_sent_by_dispatch_method()
    {
        $response = Sms::via('bar')->send('foo')->to('baz')->dispatch();

        $this->assertInstanceOf(BarDriver::class, $response);
        $this->assertEquals('foo', $response->getBody());
        $this->assertContains('baz', $response->getRecipients());
    }

    public function test_sms_builder_can_be_sent()
    {
        $response = Sms::send((new Builder)->via('bar')->to('baz')->send('foo'));

        $this->assertInstanceOf(BarDriver::class, $response);
        $this->assertEquals('foo', $response->getBody());
        $this->assertContains('baz', $response->getRecipients());
    }

    public function test_sms_builder_via_can_be_sent()
    {
        $response = Sms::via('bar')->send((new Builder)->to('baz')->send('foo'));

        $this->assertInstanceOf(BarDriver::class, $response);
        $this->assertEquals('foo', $response->getBody());
        $this->assertContains('baz', $response->getRecipients());
    }
}
