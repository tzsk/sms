<?php

namespace Tzsk\Sms\Drivers;

use Kavenegar\KavenegarApi;
use Tzsk\Sms\Contracts\Driver;

class Kavenegar extends Driver
{
    protected KavenegarApi $client;

    protected function boot(): void
    {
        $this->client = new KavenegarApi(data_get($this->settings, 'apiKey'));
    }

    public function send()
    {
        $response = collect();

        foreach ($this->recipients as $recipient) {
            $response->put(
                $recipient,
                $this->client->Send($this->sender, $recipient, $this->body)
            );
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }
}
