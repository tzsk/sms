<?php


namespace Tzsk\Sms\Drivers;


use Tzsk\Sms\Contracts\Driver;

class Ghasedak extends Driver
{
    protected array $settings;

    protected $client;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
        $this->client = curl_init();
    }

    public function send()
    {
        $sender = $this->settings['from'];
        $response = collect();
        foreach ($this->recipients as $recipient) {
            curl_setopt_array($this->client, array(
                CURLOPT_URL            => $this->settings['url'] . '/v2/sms/send/simple',
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "POST",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS     => "message=$this->body&sender=$sender&Receptor=$recipient",
                CURLOPT_HTTPHEADER     => array(
                    "apikey: " . $this->settings['apiKey'],
                ),
            ));
            $result = curl_exec($this->client);
            $response->put($recipient, $result);
        }
        curl_close($this->client);
        return (count($this->recipients) == 1) ? $response->first() : $response;
    }
}
