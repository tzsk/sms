<?php

namespace Tzsk\Sms\Provider;

use Illuminate\Support\ServiceProvider;
use Tzsk\Sms\SmsManager;

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
        $this->app->singleton('tzsk-sms', function() {
            return new SmsManager();
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