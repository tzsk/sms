<?php
namespace Tzsk\Sms\Drivers;

use Melipayamak\MelipayamakApi;
use Tzsk\Sms\Abstracts\Driver;

class Melipayamak extends Driver
{
    /**
     * Melipayamak Settings.
     *
     * @var null|object
     */
    protected $settings = null;

    /**
     * MeliPayamak Client.
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
        $this->client = new MelipayamakApi($this->settings->username, $this->settings->password);
    }

    /**
     * Determine if the sms must be a flash message or not.
     *
     * @param bool $flash
     * @return $this
     */
    public function asFlash($flash=true)
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
        try{
            $sms = $this->client->sms();
            $response = ['status' => true, 'data' =>[]];
            foreach ($this->recipients as $recipient) {
                $response = $sms->send(
                    $recipient,
                    $this->settings->from,
                    $this->body,
                    $this->settings->flash
                );
            }
        } catch(\Exception $e) {
            $response['status'][$recipient] = false;
            $response['data'][$recipient] = $e->getMessage();
        } finally {
            if (empty($data)) {
                $response['data'][$recipient] = $this->getSmsResponse(
                    json_decode($response, JSON_UNESCAPED_UNICODE)
                );
            }
        }

        $this->flash(false);

        return (object) $response;
    }

    /**
     * Get the MeliPayamak Response.
     *
     * @param $sms
     * @return object
     */
    protected function getSmsResponse($sms)
    {
        $attributes = [
            'recId',
        ];

        $res = [];
        foreach ($attributes as $attribute) {
            $res[$attribute] = $sms->$attribute;
        }

        return (object) $res;
    }
}
