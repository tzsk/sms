<?php
namespace Tzsk\Sms\Drivers;


use Twilio\Rest\Client;
use Tzsk\Sms\Contract\SendSmsInterface;

class Twilio implements SendSmsInterface
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
     * Recipient of this sms.
     *
     * @var array
     */
    protected $recipients = [];

    /**
     * Body of the SMS.
     *
     * @var string
     */
    protected $body = "";

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
     * String or Array of numbers.
     *
     * @param $numbers string|array
     * @return mixed
     * @throws \Exception
     */
    public function to($numbers)
    {
        $recipients = is_array($numbers) ? $numbers : [$numbers];
        $recipients = array_map(function($item) {
            return trim($item);
        }, array_merge($this->recipients, $recipients));

        $this->recipients = array_values(array_filter($recipients));

        if (count($this->recipients) < 1) {
            throw new \Exception("Message recipient could not be empty.");
        }

        return $this;
    }

    /**
     * Set text message body.
     *
     * @param $message string
     * @return mixed
     * @throws \Exception
     */
    public function message($message)
    {
        if (!is_string($message)) {
            throw new \Exception("Message text should be a string.");
        }
        if (trim($message) == '') {
            throw new \Exception("Message text could not be empty.");
        }
        $this->body = $message;

        return $this;
    }

    /**
     * Send text message and return response.
     *
     * @param $message
     * @return object
     */
    public function send($message)
    {
        $this->message($message);
        $response = ['status' => true, 'data' =>[]];
        foreach ($this->recipients as $recipient) {
            $sms = $this->client->account->messages->create(
                $recipient,
                array(
                    'from' => $this->settings->from,
                    'body' => $this->body
                )
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