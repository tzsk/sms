<?php

namespace Tzsk\Sms\Drivers;

use Kavenegar\KavenegarApi;
use Tzsk\Sms\Abstracts\Driver;

class Kavenegar extends Driver
{
    /**
     * Kavenegar Settings.
     *
     * @var object
     */
    protected $settings;

    /**
     * Kavenegar Client.
     *
     * @var null|KavenegarApi
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
        $this->client = new KavenegarApi($this->settings->apiKey);
    }

    /**
     * Send text message and return response.
     *
     * @return object
     */
    public function send()
    {
        $response = collect();
        foreach ($this->recipients as $recipient) {
            $response->put(
                $recipient,
                $this->client->Send($this->settings->from, $recipient, $this->body)
            );
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }
}
