<?php

namespace Tzsk\Sms\Provider;

use Tzsk\Sms\SmsManager;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Configurations that needs to be done by user.
         */
        $this->publishes([
            __DIR__.'/../Config/sms.php' => config_path('sms.php'),
        ], 'config');

        /**
         * Bind to service container.
         */
        $this->app->bind('tzsk-sms', function () {
            return new SmsManager(config('sms'));
        });
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
