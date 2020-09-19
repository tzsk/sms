<?php

namespace Tzsk\Sms\Drivers;

use GuzzleHttp\Client;
use Tzsk\Sms\Contracts\Driver;

class Linkmobility extends Driver
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

    protected function payload($recipient)
    {
        return [
            'form_params' => [
                'USER' => data_get($this->settings, 'username'),
                'PW' => data_get($this->settings, 'password'),
                'RCV' => $recipient,
                'SND' => urlencode(data_get($this->settings, 'sender')),
                'TXT' => $this->body,
            ],
        ];
    }
}
