<?php

namespace Tzsk\Sms\Drivers;

use Random\RandomException;
use Seven\Api\Client;
use Seven\Api\Exception\ForbiddenIpException;
use Seven\Api\Exception\InvalidApiKeyException;
use Seven\Api\Exception\InvalidOptionalArgumentException;
use Seven\Api\Exception\InvalidRequiredArgumentException;
use Seven\Api\Exception\MissingAccessRightsException;
use Seven\Api\Exception\SigningHashVerificationException;
use Seven\Api\Exception\UnexpectedApiResponseException;
use Seven\Api\Resource\Sms\SmsParams;
use Seven\Api\Resource\Sms\SmsResource;
use Tzsk\Sms\Contracts\Driver;

class Sms77 extends Driver
{
    protected Client $client;
    private string $sourceIdentifier = 'Laravel-SMS-Gateway';
    protected SmsResource $resource;

    protected function boot(): void
    {
        $this->client = new Client(data_get($this->settings, 'apiKey'), $this->sourceIdentifier);
        $this->resource = new SmsResource($this->client);
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

        foreach ($this->recipients as $recipient) {
            try {
                $result = $this->resource->dispatch((new SmsParams($this->body, $recipient))
                    ->setFlash(data_get($this->settings, 'flash'))
                    ->setFrom($this->sender));
            } catch (\Exception) {}

            $response->put($recipient, $result);
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }
}
