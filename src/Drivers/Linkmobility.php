<?php

namespace Tzsk\Sms\Drivers;

use GuzzleHttp\Client;
use Tzsk\Sms\Abstracts\Driver;

class Linkmobility extends Driver
{
    /**
     * Linkmobility Settings.
     *
     * @var object|null
     */
    protected $settings;

    /**
     * Http Client.
     *
     * @var Client|null
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
    protected function payload($recipient)
    {
        return [
            'form_params' => [
                'USER' => $this->settings->username,
                'PW' => $this->settings->password,
                'RCV' => $recipient,
                'SND' => urlencode($this->settings->sender),
                'TXT' => $this->body,
            ],
        ];
    }
}
