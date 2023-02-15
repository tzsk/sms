<?php

namespace Tzsk\Sms\Drivers;

use GuzzleHttp\Client;
use Tzsk\Sms\Contracts\Driver;

class Linkmobility extends Driver
{
    protected Client $client;

    protected function boot(): void
    {
        $this->client = new Client();
    }

    public function send()
    {
        /** @var \Illuminate\Support\Collection $response */
        $response = collect();

        foreach ($this->recipients as $recipient) {
            $response->put(
                $recipient,
                $this->client->request('POST', data_get($this->settings, 'url'), $this->payload($recipient))
            );
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }

    protected function payload($recipient): array
    {
        return [
            'form_params' => [
                'USER' => data_get($this->settings, 'username'),
                'PW' => data_get($this->settings, 'password'),
                'RCV' => $recipient,
                'SND' => urlencode($this->sender),
                'TXT' => $this->body,
            ],
        ];
    }
}
