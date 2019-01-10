<?php

namespace Tzsk\Sms\Drivers;

use Kavenegar\KavenegarApi;
use Tzsk\Sms\Abstracts\Driver;

class Kavenegar extends Driver
{
    /**
     * Kavenegar Settings.
     *
     * @var null|object
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
        $response = [];
        foreach ($this->recipients as $recipient) {
            $sms = $this->client->Send(
                $this->settings->from,
                $recipient,
                $this->body
            );
            $response[$recipient]['data'] = $this->getSmsResponse($sms[0]);
            $response[$recipient]['status'] = true;
        }

        return (object) $response;
    }

    /**
     * Get the Kavenegar Response.
     *
     * @param $sms
     * @return object
     */
    protected function getSmsResponse($sms)
    {
        $attributes = [
            'messageid', 'message', 'status', 'statustext',
            'sender', 'receptor', 'date', 'cost',
        ];

        $res = [];
        foreach ($attributes as $attribute) {
            $res[$attribute] = $sms->$attribute;
        }

        return (object) $res;
    }
}
