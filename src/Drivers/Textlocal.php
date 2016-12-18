<?php
namespace Tzsk\Sms\Drivers;


use GuzzleHttp\Client;
use Tzsk\Sms\Contract\SendSmsInterface;

class Textlocal implements SendSmsInterface
{
    /**
     * Textlocal Settings.
     *
     * @var object|null
     */
    protected $settings = null;

    /**
     * To Numbers array.
     *
     * @var array
     */
    protected $recipients = [];

    /**
     * Message body.
     *
     * @var null
     */
    protected $body = "";

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
     * String or Array of numbers.
     *
     * @param $numbers string|array
     * @return $this
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
        if (! is_string($message)) {
            throw new \Exception("Message text should be a string.");
        }
        if (trim($message) == '') {
            throw new \Exception("Message text could not be empty.");
        }
        $this->body = rawurlencode($message);

        return $this;
    }

    /**
     * Send text message and return response.
     *
     * @param $message
     * @return mixed
     */
    public function send($message)
    {
        $this->message($message);
        $numbers = implode(",", $this->recipients);

        $response = $this->client->request("POST", $this->settings->url, [
            "form_params" => [
                "username" => $this->settings->username,
                "hash" => $this->settings->hash,
                "numbers" => $numbers,
                "sender" => urlencode($this->settings->sender),
                "message" => $this->body,
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