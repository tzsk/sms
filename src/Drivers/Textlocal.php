<?php

namespace Tzsk\Sms\Drivers;

use GuzzleHttp\Client;
use Tzsk\Sms\Abstracts\Driver;

class Textlocal extends Driver
{
    /**
     * Textlocal Settings.
     *
     * @var object
     */
    protected $settings;

    /**
     * Http Client.
     *
     * @var Client
     */
    protected $client;

    /**
     * Construct the class with the relevant settings.
     *
     * SendSmsInterface constructor.
     * @param array $settings
     */
    public function __construct($settings)
    {
        $this->settings = (object) $settings;
        $this->client = new Client();
    }

    /**
     * Send text message and return response.
     *
     * @return mixed
     */
    public function send()
    {
        $response = collect();
        foreach ($this->recipients as $recipient) {
            $response->put(
                $recipient,
                $this->client->request('POST', $this->settings->url, $this->payload($recipient))
            );
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }

    /**
     * @param string $recipient
     * @return array
     */
    public function payload($recipient)
    {
        return [
            'form_params' => [
                'username' => $this->settings->username,
                'hash' => $this->settings->hash,
                'numbers' => $recipient,
                'sender' => urlencode($this->settings->sender),
                'message' => $this->body,
            ],
        ];
    }
}
