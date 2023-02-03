<?php

namespace Tzsk\Sms\Drivers;

use GuzzleHttp\Client;
use Tzsk\Sms\Contracts\Driver;

class Textlocal extends Driver
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

    public function payload($recipient): array
    {
        return [
            'form_params' => [
                'username' => data_get($this->settings, 'username'),
                'hash' => data_get($this->settings, 'hash'),
                'numbers' => $recipient,
                'sender' => urlencode($this->sender),
                'message' => $this->body,
            ],
        ];
    }
}
