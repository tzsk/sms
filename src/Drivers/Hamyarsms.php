<?php

namespace Tzsk\Sms\Drivers;

use SoapClient;
use Tzsk\Sms\Contracts\Driver;
use Tzsk\Sms\Exceptions\InvalidMessageException;

class Hamyarsms extends Driver
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

        $response = $this->client->SendSMS([
            'userName' => data_get($this->settings, 'username'),
            'password' => data_get($this->settings, 'password'),
            'fromNumber' => $this->sender,
            'toNumbers' => $this->recipients,
            'messageContent' => $this->body,
            'isFlash' => data_get($this->settings, 'flash'),
            'recId' => [0],
            'status' => 0x0,
        ]);

        return $response;
    }
}
