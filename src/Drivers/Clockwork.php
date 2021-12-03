<?php

namespace Tzsk\Sms\Drivers;

use mediaburst\ClockworkSMS\Clockwork as ClockworkClient;
use Tzsk\Sms\Contracts\Driver;

class Clockwork extends Driver
{
    protected ClockworkClient $client;

    public function __construct(array $settings)
    {
        parent::__construct($settings);

        $this->client = new ClockworkClient(data_get($this->settings, 'key'));
    }

    public function send()
    {
        $response = collect();
        foreach ($this->recipients as $recipient) {
            $response->put($recipient, $this->client->send([
                'to' => $recipient,
                'message' => $this->body,
            ]));
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }
}
