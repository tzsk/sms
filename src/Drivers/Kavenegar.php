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
    protected $settings = null;

    /**
     * Kavenegar Client.
     *
     * @var null|KavenegarApi
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
        $this->client = new KavenegarApi($this->settings->apiKey);
    }

    /**
     * Send text message and return response.
     *
     * @return object
     */
    public function send()
    {
        try {
            $response = ['status' => true, 'data' =>[]];
            foreach ($this->recipients as $recipient) {
                $sms = $this->client->Send(
                    $this->settings->from,
                    $recipient,
                    $this->body
                );
                $response['data'][$recipient] = $this->getSmsResponse(
                    json_decode($sms, true)
                );
            }
        } catch (\Exception $e) {
            $response['status'][$recipient] = false;
            $response['data'][$recipient] = $e->getMessage();
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
