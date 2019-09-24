<?php

namespace Tzsk\Sms\Drivers;

use Tzsk\Sms\Abstracts\Driver;
use \SoapClient;

class Tsms extends Driver
{
    /**
     * Tsms Settings.
     *
     * @var null|object
     */
    protected $settings;

    /**
     * Tsms Client.
     *
     * @var null|SoapClient
     */
    protected $client;

    /**
     * Construct the class with the relevant settings.
     *
     * @param $settings
     * @throws \SoapFault
     */
    public function __construct($settings)
    {
        $this->settings = (object) $settings;
    }

    /**
     * Send text message and return response.
     *
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send()
    {
        $this->client = new SoapClient($this->settings->url);
        $response = collect();
        foreach ($this->recipients as $recipient) {
            $result = $this->client->sendSms(
                $this->settings->username,
                $this->settings->password,
                [$this->settings->from],
                [$recipient],
                [$this->body],
                [],
                rand()
            );

            $response->put($recipient, $result);
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }
}
