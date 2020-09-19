<?php

namespace Tzsk\Sms\Drivers;

use Aws\Sns\SnsClient;
use Tzsk\Sms\Contracts\Driver;

class Sns extends Driver
{
    protected array $settings;

    protected SnsClient $client;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
        $this->client = new SnsClient([
            'credentials' => [
                'key' => data_get($this->settings, 'key'),
                'secret' => data_get($this->settings, 'secret'),
            ],
            'region' => data_get($this->settings, 'region'),
            'version' => '2010-03-31',
        ]);
    }

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

    protected function payload($recipient)
    {
        return [
            'Message' => $this->body,
            'MessageAttributes' => [
                'AWS.SNS.SMS.SenderID' => [
                    'DataType' => 'String',
                    'StringValue' => data_get($this->settings, 'sender'),
                ],
                'AWS.SNS.SMS.SMSType' => [
                    'DataType' => 'String',
                    'StringValue' => data_get($this->settings, 'type'),
                ],
            ],
            'PhoneNumber' => $recipient,
        ];
    }
}
