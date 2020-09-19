<?php

namespace Tzsk\Sms\Drivers;

use SMSGatewayMe\Client\Api\MessageApi;
use SMSGatewayMe\Client\ApiClient;
use SMSGatewayMe\Client\Configuration;
use SMSGatewayMe\Client\Model\SendMessageRequest;
use Tzsk\Sms\Contracts\Driver;

class SmsGatewayMe extends Driver
{
    protected array $settings;

    protected MessageApi $client;

    public function __construct(array $settings)
    {
        $this->settings = $settings;

        $config = Configuration::getDefaultConfiguration()
            ->setApiKey('Authorization', data_get($this->settings, 'apiToken'));
        $this->client = new MessageApi(new ApiClient($config));
    }

    public function send()
    {
        $response = collect();
        foreach ($this->recipients as $recipient) {
            $response->put(
                $recipient,
                $this->client->sendMessages($this->payload($recipient))
            );
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }

    protected function payload($recipient)
    {
        return new SendMessageRequest([
            'phoneNumber' => $recipient,
            'message' => $this->body,
            'deviceId' => data_get($this->settings, 'from'),
        ]);
    }
}
