<?php

namespace Tzsk\Sms\Drivers;

use GuzzleHttp\Client;
use Tzsk\Sms\Contracts\Driver;

class Textlocal extends Driver
{
    protected array $settings;

    protected Client $client;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
        $this->client = new Client();
    }

    public function send()
    {
        $response = collect();
        foreach ($this->recipients as $recipient) {
            $response->put(
                $recipient,
                $this->client->request('POST', data_get($this->settings, 'url'), $this->payload($recipient))
            );
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }

    public function payload($recipient)
    {
        return [
            'form_params' => [
                'username' => data_get($this->settings, 'username'),
                'hash' => data_get($this->settings, 'hash'),
                'numbers' => $recipient,
                'sender' => urlencode(data_get($this->settings, 'sender')),
                'message' => $this->body,
            ],
        ];
    }
}
