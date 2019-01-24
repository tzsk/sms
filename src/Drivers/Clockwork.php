<?php

namespace Tzsk\Sms\Drivers;

use Tzsk\Sms\Abstracts\Driver;
use mediaburst\ClockworkSMS\Clockwork as ClockworkClient;

class Clockwork extends Driver
{
    /**
     * Settings.
     *
     * @var object
     */
    protected $settings;

    /**
     * Client.
     *
     * @var ClockworkClient
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
        $this->client = new ClockworkClient($this->settings->key);
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
            $response->put($recipient, $this->client->send([
                'to' => $recipient,
                'message' => $this->body,
            ]));
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }
}
