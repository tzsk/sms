<?php

namespace Tzsk\Sms\Drivers;

use Tzsk\Sms\Abstracts\Driver;
use SMSGatewayMe\Client\ApiClient;
use SMSGatewayMe\Client\Configuration;
use SMSGatewayMe\Client\Api\MessageApi;
use SMSGatewayMe\Client\Model\SendMessageRequest;

class SmsGatewayMe extends Driver
{
    /**
     * SMSGatewayMe Settings.
     *
     * @var object
     */
    protected $settings;

    /**
     * SMSGatewayMe Client.
     *
     * @var SMSGatewayMe\Client\Api\MessageApi
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

        $config = Configuration::getDefaultConfiguration();
        $config->setApiKey('Authorization', $this->settings->apiToken);
        $apiClient = new ApiClient($config);
        $this->client = new MessageApi($apiClient);
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
                $this->client->sendMessages($this->payload($recipient))
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
        return new SendMessageRequest([
            'phoneNumber' => $recipient,
            'message' => $this->body,
            'deviceId' => $this->settings->from
        ]);
    }
}
