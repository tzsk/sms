<?php

namespace Tzsk\Sms\Drivers;

use GuzzleHttp\Client;
use Tzsk\Sms\Contracts\Driver;

class Ghasedak extends Driver
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
            $result = $this->client->request(
                'POST',
                $this->settings['url'] . '/v2/sms/send/simple',
                [
                    'form_params' => [
                        'Receptor' => $recipient,
                        'sender' => $this->settings['from'],
                        'message' => $this->body,
                    ],
                    'headers' => [
                        'apikey' => $this->settings['apiKey'],
                    ],
                ]
            );
            $response->put($recipient, $result);
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }
}
