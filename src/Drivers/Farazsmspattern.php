<?php

namespace Tzsk\Sms\Drivers;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Tzsk\Sms\Contracts\Driver;

class Farazsmspattern extends Driver
{
    protected Client $client;

    public function send()
    {
        /** @var \Illuminate\Support\Collection $response */
        $response = collect();
        foreach ($this->recipients as $recipient) {
            $result = $this->client->request(
                'GET',
                data_get($this->settings, 'url').'?'.Arr::query($this->payload($recipient)),
            );

            $response->put($recipient, (string) $result->getBody());
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }

    protected function payload($recipient): array
    {
        $body_data = preg_split('/\r\n|[\r\n]/', $this->body);
        $input_data = [];
        $pattern_code = (explode('=', $body_data[0]))[1];
        foreach ($body_data as $key => $datum) {
            if ($key === 0) {
                continue;
            }
            $key_value = explode('=', $datum);
            $input_data[trim($key_value[0])] = trim($key_value[1]);
        }

        return [
            'username' => data_get($this->settings, 'username'),
            'password' => data_get($this->settings, 'password'),
            'from' => $this->sender,
            'to' => json_encode([$recipient]),
            'pattern_code' => $pattern_code,
            'input_data' => json_encode($input_data),

        ];
    }

    protected function boot(): void
    {
        $this->client = new Client();
    }
}
