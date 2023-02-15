<?php

namespace Tzsk\Sms\Drivers;

use Twilio\Rest\Client;
use Tzsk\Sms\Contracts\Driver;

class Twilio extends Driver
{
    protected Client $client;

    protected function boot(): void
    {
        $this->client = new Client(data_get($this->settings, 'sid'), data_get($this->settings, 'token'));
    }

    public function send()
    {
        $response = collect();
        foreach ($this->recipients as $recipient) {
            /**
             * @psalm-suppress UndefinedMagicPropertyFetch
             */
            $result = $this->client->account->messages->create(
                $recipient,
                ['from' => $this->sender, 'body' => $this->body]
            );

            $response->put($recipient, $result);
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }
}
