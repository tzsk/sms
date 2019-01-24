<?php

namespace Tzsk\Sms\Drivers;

use Aws\Sns\SnsClient;
use Tzsk\Sms\Abstracts\Driver;

class Sns extends Driver
{
    /**
     * SNS Settings.
     *
     * @var object
     */
    protected $settings;

    /**
     * SNS Client.
     *
     * @var SnsClient
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
        $this->client = SnsClient::factory([
            'credentials' => [
                'key' => $this->settings->key,
                'secret' => $this->settings->secret,
            ],
            'region' => $this->settings->region,
            'version' => '2010-03-31',
        ]);
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
                $this->client->publish($this->payload($recipient))
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
            'Message' => $this->body,
            'MessageAttributes' => [
                'AWS.SNS.SMS.SenderID' => [
                    'DataType' => 'String',
                    'StringValue' => $this->settings->sender,
                ],
                'AWS.SNS.SMS.SMSType' => [
                    'DataType' => 'String',
                    'StringValue' => $this->settings->type,
                ],
            ],
            'PhoneNumber' => $recipient,
        ];
    }
}
