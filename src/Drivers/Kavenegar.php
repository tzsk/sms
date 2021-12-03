<?php

namespace Tzsk\Sms\Drivers;

use Kavenegar\KavenegarApi;
use Tzsk\Sms\Contracts\Driver;

class Kavenegar extends Driver
{
    protected KavenegarApi $client;

    public function __construct(array $settings)
    {
        parent::__construct($settings);

        $this->client = new KavenegarApi(data_get($this->settings, 'apiKey'));
    }

    public function send()
    {
        $response = collect();
        foreach ($this->recipients as $recipient) {
            $response->put(
                $recipient,
                $this->client->Send(data_get($this->settings, 'from'), $recipient, $this->body)
            );
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }
}
