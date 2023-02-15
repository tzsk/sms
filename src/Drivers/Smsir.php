<?php

namespace Tzsk\Sms\Drivers;

use GuzzleHttp\Client;
use Tzsk\Sms\Contracts\Driver;
use Tzsk\Sms\Exceptions\InvalidSmsConfigurationException;

class Smsir extends Driver
{
    protected Client $client;

    protected function boot(): void
    {
        $this->client = new Client();
    }

    public function send()
    {
        $token = $this->getToken();
        /** @var \Illuminate\Support\Collection $response */
        $response = collect();

        foreach ($this->recipients as $recipient) {
            $result = $this->client->request(
                'POST',
                data_get($this->settings, 'url').'/api/MessageSend',
                $this->payload($recipient, $token)
            );
            $response->put($recipient, $result);
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }

    /**
     * @param  string  $recipient
     * @param  string  $token
     * @return array
     */
    protected function payload($recipient, $token)
    {
        return [
            'json' => [
                'Messages' => [$this->body],
                'MobileNumbers' => [$recipient],
                'LineNumber' => $this->sender,
            ],
            'headers' => [
                'x-sms-ir-secure-token' => $token,
            ],
            'connect_timeout' => 30,
        ];
    }

    protected function getToken()
    {
        $body = [
            'UserApiKey' => data_get($this->settings, 'apiKey'),
            'SecretKey' => data_get($this->settings, 'secretKey'),
        ];
        $response = $this->client->post(
            data_get($this->settings, 'url').'/api/Token',
            ['json' => $body, 'connect_timeout' => 30]
        );

        $body = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        if (empty($body['TokenKey'])) {
            throw new InvalidSmsConfigurationException('Smsir token could not be generated.');
        }

        return $body['TokenKey'];
    }
}
