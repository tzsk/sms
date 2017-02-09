<?php
namespace Tzsk\Sms\Drivers;

use Twilio\Rest\Client;
use Tzsk\Sms\Contract\SendSmsInterface;

class Twilio extends MasterDriver implements SendSmsInterface
{
    /**
     * Twilio Settings.
     *
     * @var null|object
     */
    protected $settings = null;

    /**
     * Twilio Client.
     *
     * @var null|Client
     */
    protected $client = null;

    /**
     * Construct the class with the relevant settings.
     *
     * SendSmsInterface constructor.
     * @param $settings object
     */
    public function __construct($settings)
    {
        $this->settings = (object) $settings;
        $this->client = new Client($this->settings->sid, $this->settings->token);
    }

    /**
     * Send text message and return response.
     *
     * @return object
     */
    public function send()
    {
        $response = ['status' => true, 'data' =>[]];
        foreach ($this->recipients as $recipient) {
            $sms = $this->client->account->messages->create(
                $recipient,
                ['from' => $this->settings->from, 'body' => $this->body]
            );

            $response['data'][$recipient] = $this->getSmsResponse($sms);
        }

        return (object) $response;
    }

    /**
     * Get the Twilio Response.
     *
     * @param $sms
     * @return object
     */
    protected function getSmsResponse($sms)
    {
        $attributes = [
            'accountSid', 'apiVersion', 'body', 'direction', 'errorCode',
            'errorMessage', 'from', 'numMedia', 'numSegments', 'price',
            'priceUnit', 'sid', 'status', 'subresourceUris', 'to', 'uri',
            'dateCreated', 'dateUpdated', 'dateSent',
        ];

        $res = [];
        foreach ($attributes as $attribute) {
            $res[$attribute] = $sms->$attribute;
        }

        return (object) $res;
    }
}
