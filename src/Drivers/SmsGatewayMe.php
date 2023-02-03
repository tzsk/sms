<?php

namespace Tzsk\Sms\Drivers;

use SMSGatewayMe\Client\Api\MessageApi;
use SMSGatewayMe\Client\ApiClient;
use SMSGatewayMe\Client\Configuration;
use SMSGatewayMe\Client\Model\SendMessageRequest;
use Tzsk\Sms\Contracts\Driver;

class SmsGatewayMe extends Driver
{
    protected MessageApi $client;

    protected function boot(): void
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey(
            'Authorization',
            data_get($this->settings, 'apiToken')
        );

        $this->client = new MessageApi(new ApiClient($config));
    }

    public function send()
    {
        /** @var \Illuminate\Support\Collection $response */
        $response = collect();

        foreach ($this->recipients as $recipient) {
            $response->put(
                $recipient,
                $this->client->sendMessages([$this->payload($recipient)])
            );
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }

    protected function payload($recipient): SendMessageRequest
    {
        return new SendMessageRequest([
            'phoneNumber' => $recipient,
            'message' => $this->body,
            'deviceId' => $this->sender,
        ]);
    }
}
