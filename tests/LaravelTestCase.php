<?php

namespace Tzsk\Sms\Tests;

use Orchestra\Testbench\TestCase;

class LaravelTestCase extends TestCase
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
        $app['config']->set('sms', require __DIR__.'/../src/Config/sms.php');
    }
}
