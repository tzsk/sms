<?php

namespace Tzsk\Sms\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Tzsk\Sms\Tests\Mocks\Drivers\BarDriver;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return ['Tzsk\Sms\Provider\SmsServiceProvider'];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Sms' => 'Tzsk\Sms\Facade\Sms',
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $settings = require __DIR__.'/../src/Config/sms.php';
        $settings['drivers']['bar'] = ['key' => 'foo'];
        $settings['map']['bar'] = BarDriver::class;

        $app['config']->set('sms', $settings);
    }
}
