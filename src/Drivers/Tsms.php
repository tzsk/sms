<?php

namespace Tzsk\Sms\Drivers;

use Tzsk\Sms\Contracts\Driver;
use \SoapClient;

class Tsms extends Driver
{
    protected array $settings;

    protected SoapClient $client;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function send()
    {
        $this->client = new SoapClient(data_get($this->settings, 'url'));
        $response = collect();
        foreach ($this->recipients as $recipient) {
            $result = $this->client->sendSms(
                data_get($this->settings, 'username'),
                data_get($this->settings, 'password'),
                [data_get($this->settings, 'from')],
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
