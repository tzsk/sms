<?php
namespace Tzsk\Sms\Drivers;

use GuzzleHttp\Client;
use Tzsk\Sms\Abstracts\Driver;

class Smsir extends Driver
{
    /**
     * Smsir Settings.
     *
     * @var null|object
     */
    protected $settings = null;

    /**
     * Smsir Client.
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
        $this->client = new Client();
    }

    /**
     * Get token.
     *
     * @return mixed - the Token for use api
     */
    private function getToken()
    {
        $body = [
            'UserApiKey' => $this->settings->apiKey,
            'SecretKey'=> $this->settings->secretKey,
        ];
        $response = $this->client->post(
            $this->settings->url.'api/Token',
            [
                'json' => $body,
                'connect_timeout' => 30
            ]
        );

        return json_decode((string) $response->getBody(), true)['TokenKey'];
    }

    /**
     * Send with ultraFast method
     *
     * @param array $parameters
     * @param $template_id
     * @param $number
     * @return mixed
     */
    public function ultraFastSend(array $parameters, $template_id)
    {
        $responses = [];

        $params = [];

        foreach ($parameters as $key => $value) {
            $params[] = ['Parameter' => $key, 'ParameterValue' => $value];
        }

        foreach ($this->recipients as $recipient) {
            $body = [
                'ParameterArray' => $params,
                'TemplateId' => $template_id,
                'Mobile' => $recipient,
            ];
            $response = $this->client->post(
                $this->settings->url.'api/UltraFastSend',
                [
                    'json' => $this->body,
                    'headers' => [
                        'x-sms-ir-secure-token' => $this->getToken(),
                    ],
                    'connect_timeout' => 30
                ]
            );
            $responses[$recipient] = $this->getResponseData($response);
        }

        return (object) $responses;
    }

    /**
     * Send text message and return response.
     *
     * @return object
     */
    public function send()
    {
        $body = [
            'Messages' => $this->body,
            'MobileNumbers' => [$this->recipients],
            'LineNumber' => $this->settings->from,
        ];
        $response = $this->client->request("POST", $this->settings->url.'api/MessageSend',
            [
                'json' => $body,
                'headers' => [
                    'x-sms-ir-secure-token' => $this->getToken()
                ],
                'connect_timeout' => 30
            ]
        );

        $data = $this->getResponseData($response);

        return (object) array_merge($data, ["status" => true]);
    }

    /**
     * Get the response data.
     *
     * @param  object $response
     * @return array|object
     */
    protected function getResponseData($response)
    {
        if ($response->getStatusCode() != 200) {
            return ["status" => false, "message" => "Request Error. " . $response->getReasonPhrase()];
        }

        $data = json_decode((string) $response->getBody(), true);

        if ($data["status"] != "success") {
            return ["status" => false, "message" => "Something went wrong.", "data" => $data];
        }

        return $data;
    }
}
