<?php

namespace Tzsk\Sms\Drivers;

use SoapClient;
use Tzsk\Sms\Contracts\Driver;

class Rahyabcp extends Driver
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
            $result = $this->client->SendSms([
                'username' => data_get($this->settings, 'username'),
                'password' => data_get($this->settings, 'password'),
                'from' => data_get($this->settings, 'from'),
                'to' => [$recipient],
                'text' => $this->body,
                'isflash' => data_get($this->settings, 'flash'),
                'udh' => "",
                'recId' => [0],
                'status' => [0],
            ]);

            $response->put($recipient, $result);
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }
}
