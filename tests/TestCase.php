<?php

namespace Tzsk\Sms\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Tzsk\Sms\SmsServiceProvider;
use Tzsk\Sms\Tests\Mocks\Drivers\BarDriver;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            SmsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('sms.drivers.sns.region', 'us-east-1');
        $app['config']->set('sms.drivers.bar', ['key' => 'foo']);
        $app['config']->set('sms.map.bar', BarDriver::class);
    }
}
