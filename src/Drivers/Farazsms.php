<?php

namespace Tzsk\Sms\Drivers;

use GuzzleHttp\Client;
use Tzsk\Sms\Abstracts\Driver;

class Farazsms extends Driver
{
    /**
     * Farazsms Settings.
     *
     * @var null|object
     */
    protected $settings;

    /**
     * Farazsms Client.
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
        $response = collect();

        foreach ($this->recipients as $recipient) {
            $result = $this->client->request(
                'POST',
                $this->settings->url,
                $this->payload($recipient)
            );
            $response->put($recipient, json_decode($result->getBody()));
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }

    /**
     * @param string $recipient
     * @return array
     */
    protected function payload($recipient)
    {
        return [
            'form_params' => [
                'uname' => $this->settings->username,
                'pass' => $this->settings->password,
                'from' => $this->settings->from,
                'message' => $this->body,
                'to' => json_encode([$recipient]),
                'op'=>'send'
            ],
        ];
    }
}
