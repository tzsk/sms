<?php

namespace Tzsk\Sms\Drivers;

use GuzzleHttp\Client;
use Tzsk\Sms\Contracts\Driver;

class SmsApi extends Driver
{
    protected Client $client;

    private array $options = [];

    protected function boot(): void
    {
        $this->client = new Client();
        $this->options['from'] = $this->settings['from'];
    }

    /**
     * Provides a way to pass additional valid parameters or override existing ones
     */
    public function with(array $options): self
    {
        if (! empty(array_diff(array_keys($options), ['from', 'sname', 'cc', 'sid', 'ur', 'dr', 'stime', 'unicode', 'mms', 'mmstype', 'mmsurl']))) {
            throw new \Exception(__METHOD__.' contains invalid options');
        }

        foreach ($options as $option => $value) {
            $this->options[$option] = $value;
        }

        return $this;
    }

    public function send()
    {
        /** @var \Illuminate\Support\Collection $response */
        $response = collect();
        foreach ($this->recipients as $recipient) {
            $response->put(
                $recipient,
                $this->client->post(
                    data_get($this->settings, 'url'),
                    [
                        'form_params' => $this->payload($recipient),
                        'verify' => false,
                    ]
                )
            );
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }

    public function payload($recipient): array
    {
        $payload = [
            'un' => data_get($this->settings, 'username'),
            'ps' => data_get($this->settings, 'password'),
            'from' => $this->options['from'],
            'to' => $recipient,
            'cc' => data_get($this->settings, 'cc'),
            'm' => $this->body,
        ];

        return array_merge($payload, $this->options);
    }
}
