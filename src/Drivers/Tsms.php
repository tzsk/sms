<?php

namespace Tzsk\Sms\Drivers;

use SoapClient;
use Tzsk\Sms\Contracts\Driver;

class Tsms extends Driver
{
    protected SoapClient $client;

    protected function boot(): void
    {
        $this->client = new SoapClient(data_get($this->settings, 'url'));
    }

    public function send()
    {
        $response = collect();

        foreach ($this->recipients as $recipient) {
            $result = $this->client->sendSms(
                data_get($this->settings, 'username'),
                data_get($this->settings, 'password'),
                [$this->sender],
                [$recipient],
                [$this->body],
                [],
                rand()
            );

            $response->put($recipient, $result);
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }
}
