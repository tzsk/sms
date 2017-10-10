<?php
namespace Tzsk\Sms\Drivers;


use GuzzleHttp\Client;
use Tzsk\Sms\Contract\SendSmsInterface;

class Linkmobility extends MasterDriver implements SendSmsInterface
{
    /**
     * Textlocal Settings.
     *
     * @var object|null
     */
    protected $settings = null;

    /**
     * Http Client.
     *
     * @var Client|null
     */
    protected $client = null;

    /**
     * Construct the class with the relevant settings.
     *
     * SendSmsInterface constructor.
     * @param array $settings
     */
    public function __construct($settings)
    {
        $this->settings = (object) $settings;
        $this->client = new Client();
    }

    /**
     * Send text message and return response.
     *
     * @return mixed
     */
    public function send()
    {
        $numbers = implode(",", $this->recipients);

        $response = $this->client->request("POST", $this->settings->url, [
            "form_params" => [
                "USER" => $this->settings->username,
                "PW" => $this->settings->password,
                "RCV"  => $numbers,
                "SND" => urlencode($this->settings->sender),
                "TXT" => $this->body,
            ],
        ]);

        if ($response->getStatusCode() != 200) {
            return (object) ["status" => false, "message" => "Request Error. " . $response->getReasonPhrase()];
        }

        $data = json_decode((string) $response->getBody(), true);

        if ($data["status"] != "success") {
            return (object) ["status" => false, "message" => "Something went wrong.", "data" => $data];
        }

        return (object) array_merge($data, ["status" => true]);

    }
}
