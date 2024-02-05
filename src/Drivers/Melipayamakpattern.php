<?php

namespace Tzsk\Sms\Drivers;

use Melipayamak\MelipayamakApi;
use Tzsk\Sms\Contracts\Driver;

class Melipayamakpattern extends Driver
{
    protected MelipayamakApi $client;

    protected function boot(): void
    {
        $this->client = new MelipayamakApi(
            data_get($this->settings, 'username'),
            data_get($this->settings, 'password')
        );
    }

    public function send()
    {
        $response = collect();
        $body_data = preg_split('/\r\n|[\r\n]/', $this->body);
        $input_data = [];
        $pattern_code = (explode('=', $body_data[0]))[1];
        foreach ($body_data as $key => $datum) {
            if ($key === 0) {
                continue;
            }
            $key_value = explode('=', $datum);
            $input_data[] = trim($key_value[1]);
        }
        foreach ($this->recipients as $recipient) {
            $response->put($recipient, $this->client->sms('soap')->sendByBaseNumber(
                $input_data,
                $recipient,
                $pattern_code
            ));
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }
}
