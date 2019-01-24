<?php

namespace Tzsk\Sms\Drivers;

use GuzzleHttp\Client;
use Tzsk\Sms\Abstracts\Driver;

class Smsir extends Driver
{
    /**
     * Smsir Settings.
     *
     * @var null|object
     */
    protected $settings;

    /**
     * Smsir Client.
     *
     * @var null|Client
     */
    protected $client;

    /**
     * Construct the class with the relevant settings.
     *
     * SendSmsInterface constructor.
     * @param $settings object
     */
    public function __construct($settings)
    {
        $this->settings = (object) $settings;
        $this->client = new Client();
    }

    /**
     * Send text message and return response.
     *
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send()
    {
        $token = $this->getToken();
        $response = collect();

        foreach ($this->recipients as $recipient) {
            $result = $this->client->request(
                'POST',
                $this->settings->url.'api/MessageSend',
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
                'LineNumber' => $this->settings->from,
            ],
            'headers' => [
                'x-sms-ir-secure-token' => $token
            ],
            'connect_timeout' => 30
        ];
    }

    /**
     * Get token.
     *
     * @throws \Exception
     * @return string
     */
    protected function getToken()
    {
        $body = [
            'UserApiKey' => $this->settings->apiKey,
            'SecretKey' => $this->settings->secretKey,
        ];
        $response = $this->client->post(
            $this->settings->url.'api/Token',
            ['json' => $body, 'connect_timeout' => 30]
        );

        $body = json_decode((string) $response->getBody(), true);
        if (empty($body['TokenKey'])) {
            throw new \Exception('Smsir token could not be generated.');
        }

        return $body['TokenKey'];
    }
}
