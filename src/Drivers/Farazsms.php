<?php

namespace Tzsk\Sms\Drivers;

use Tzsk\Sms\Abstracts\Driver;
use \SoapClient;

class Farazsms extends Driver
{
    /**
     * Tsms Settings.
     *
     * @var null|object
     */
    protected $settings;

    /**
     * Smsir Client.
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
            $response->put($recipient, json_decode($result));
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }

    /**
     * @param string $recipient
     * @param string $token
     * @return array
     */
    protected function payload($recipient)
    {
        return [
            'uname' => $this->settings->username,
            'pass' => $this->settings->password,
            'from' => $this->settings->from,
            'message' => $this->body,
            'to' => json_encode([$recipient]),
            'op'=>'send'
        ];
    }
}
