<?php

namespace Tzsk\Sms\Drivers;

use GuzzleHttp\Client;
use Tzsk\Sms\Contracts\Driver;

class Farazsms extends Driver
{
    protected Client $client;

    protected function boot(): void
    {
        $this->client = new Client();
    }

    public function send()
    {
        $response = collect();

        foreach ($this->recipients as $recipient) {
            $result = $this->client->request(
                'POST',
                data_get($this->settings, 'url'),
                $this->payload($recipient)
            );
            $response->put($recipient, json_decode((string) $result->getBody()));
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }

    protected function payload($recipient): array
    {
        return [
            'form_params' => [
                'uname' => data_get($this->settings, 'username'),
                'pass' => data_get($this->settings, 'password'),
                'from' => $this->sender,
                'message' => $this->body,
                'to' => json_encode([$recipient]),
                'op' => 'send',
            ],
        ];
    }
}
