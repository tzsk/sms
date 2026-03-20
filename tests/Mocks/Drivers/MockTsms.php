<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

use Tzsk\Sms\Drivers\Tsms;

class MockTsms extends Tsms
{
    use MockCommon;

    public function __construct(array $settings = [])
    {
        parent::__construct(config('sms.drivers.tsms'));
    }

    protected function boot(): void
    {
        // Disable loading actual WSDL during tests
        $this->client = \Mockery::mock(\SoapClient::class);
    }
}
