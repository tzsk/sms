<?php

namespace Tzsk\Sms\Drivers;

use Melipayamak\MelipayamakApi;
use Tzsk\Sms\Contracts\Driver;

class Melipayamak extends Driver
{
    protected MelipayamakApi $client;

    protected function boot(): void
    {
        $this->client = new MelipayamakApi(
            data_get($this->settings, 'username'),
            data_get($this->settings, 'password')
        );
    }

    public function asFlash($flash = true): self
    {
        $this->settings['flash'] = $flash;

        return $this;
    }

    public function send()
    {
        $response = collect();
        foreach ($this->recipients as $recipient) {
            $response->put(
                $recipient,
                $this->client->sms()->send(
                    $recipient,
                    $this->sender,
                    $this->body,
                    data_get($this->settings, 'flash')
                )
            );
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }
}
