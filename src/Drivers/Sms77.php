<?php

namespace Tzsk\Sms\Drivers;

use Sms77\Api\Client;
use Sms77\Api\Params\SmsParams;
use Tzsk\Sms\Contracts\Driver;

class Sms77 extends Driver
{
    protected Client $client;

    private string $sourceIdentifier = 'Laravel-SMS-Gateway';

    protected function boot(): void
    {
        $this->client = new Client(data_get($this->settings, 'apiKey'), $this->sourceIdentifier);
    }

    public function asFlash(bool $flash = true): self
    {
        $this->settings['flash'] = $flash;

        return $this;
    }

    public function send()
    {
        /** @var \Illuminate\Support\Collection $response */
        $response = collect();
        $params = (new SmsParams)
            ->setFlash(data_get($this->settings, 'flash'))
            ->setFrom($this->sender)
            ->setText($this->body);

        foreach ($this->recipients as $recipient) {
            $result = $this->client->smsJson($params->setTo($recipient));

            $response->put($recipient, $result);
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }
}
