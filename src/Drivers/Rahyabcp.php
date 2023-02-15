<?php

namespace Tzsk\Sms\Drivers;

use SoapClient;
use Tzsk\Sms\Contracts\Driver;
use Tzsk\Sms\Exceptions\InvalidMessageException;

class Rahyabcp extends Driver
{
    protected SoapClient $client;

    protected function boot(): void
    {
        $this->client = new SoapClient(data_get($this->settings, 'url'));
    }

    public function send()
    {
        if (count($this->recipients) > 100) {
            throw new InvalidMessageException('Recipients cannot be more than 100 numbers.');
        }

        $response = $this->client->SendSms([
            'username' => data_get($this->settings, 'username'),
            'password' => data_get($this->settings, 'password'),
            'from' => $this->sender,
            'to' => $this->recipients,
            'text' => $this->body,
            'isflash' => data_get($this->settings, 'flash'),
            'udh' => '',
            'recId' => [0],
            'status' => [0],
        ]);

        return $response;
    }
}
