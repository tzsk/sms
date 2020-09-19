<?php

namespace Tzsk\Sms\Drivers;

use GuzzleHttp\Client;
use Tzsk\Sms\Contracts\Driver;

class Smsir extends Driver
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
        $token = $this->getToken();
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
     * @param string $recipient
     * @param string $token
     * @return array
     */
    protected function payload($recipient, $token)
    {
        return [
            'json' => [
                'Messages' => [$this->body],
                'MobileNumbers' => [$recipient],
                'LineNumber' => data_get($this->settings, 'from'),
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

        $body = json_decode((string) $response->getBody(), true);
        if (empty($body['TokenKey'])) {
            throw new \Exception('Smsir token could not be generated.');
        }

        return $body['TokenKey'];
    }
}
