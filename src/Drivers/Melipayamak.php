<?php

namespace Tzsk\Sms\Drivers;

use Tzsk\Sms\Abstracts\Driver;
use Melipayamak\MelipayamakApi;

class Melipayamak extends Driver
{
    /**
     * Melipayamak Settings.
     *
     * @var null|object
     */
    protected $settings;

    /**
     * Melipayamak Client.
     *
     * @var null|MelipayamakApi
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
        $this->client = new MelipayamakApi($this->settings->username, $this->settings->password);
    }

    /**
     * Determine if the sms must be a flash message or not.
     *
     * @param bool $flash
     * @return $this
     */
    public function asFlash($flash = true)
    {
        $this->settings->flash = $flash;

        return $this;
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
            $sms = $this->client->sms()->send(
                $recipient,
                $this->settings->from,
                $this->body,
                $this->settings->flash
            );
            $response[$recipient]['data'] = $this->getSmsResponse($sms);
            $response[$recipient]['status'] = true;
        }

        return (object) $response;
    }

    /**
     * Get the Melipayamak Response.
     *
     * @param $sms
     * @return object
     */
    protected function getSmsResponse($sms)
    {
        $sms = json_decode($sms, true);
        $attributes = [
            'Value', 'RetStatus', 'StrRetStatus'
        ];

        $res = [];
        foreach ($attributes as $attribute) {
            $res[$attribute] = $sms[$attribute];
        }

        return (object) $res;
    }
}
