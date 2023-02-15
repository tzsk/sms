<?php

namespace Tzsk\Sms\Drivers;

use Illuminate\Support\Facades\Http;
use Tzsk\Sms\Contracts\Driver;
use Tzsk\Sms\Exceptions\InvalidMessageException;
use Tzsk\Sms\Exceptions\InvalidSmsConfigurationException;

class Rahyabir extends Driver
{
    private ?string $baseUrl;

    private ?string $username;

    private ?string $password;

    private ?string $company;

    private ?string $number;

    private ?string $token;

    protected function boot(): void
    {
        $this->baseUrl = trim($this->settings['url'], '/');
        $this->username = $this->settings['username'];
        $this->password = $this->settings['password'];
        $this->number = $this->sender;
        $this->company = $this->settings['company'];
        $this->token = cache('rahyabir_token');
    }

    public function send()
    {
        if (empty($this->token)) {
            $this->login();
        }

        if (count($this->recipients) > 100) {
            throw new InvalidMessageException('Recipients cannot be more than 100 numbers.');
        }

        $response = Http::withToken($this->token)->withHeaders($this->getHttpHeaders())
            ->post($this->getSendSmsApiUrl(), [
                'message' => $this->body,
                'destinationAddress' => $this->recipients,
                'number' => $this->number,
                'userName' => $this->username,
                'password' => $this->password,
                'company' => $this->company,
            ]);

        $jsonResponse = $response->json();

        if ($response->status() == 401) {
            cache()->forget('rahyabir_token');

            return $this->send();
        }

        if ($this->isResponseValid($jsonResponse)) {
            return $jsonResponse;
        }

        throw new InvalidMessageException(json_encode($jsonResponse), $response->status());
    }

    private function login(): void
    {
        $this->validateConfiguration();

        $response = Http::post($this->getLoginApiUrl(), [
            'userName' => "{$this->username}@{$this->company}",
            'password' => $this->password,
            'company' => $this->company,
        ]);

        $responseBody = $response->body();
        $responsejson = $response->json();

        if (isset($responsejson['status'])) {
            throw new InvalidMessageException($responsejson['title'], $responsejson['status']);
        }

        cache([
            'rahyabir_token' => $responseBody,
        ], now()->addDays($this->getTokenValidDay()));

        $this->token = $responseBody;
    }

    private function validateConfiguration(): void
    {
        if (empty($this->baseUrl)) {
            throw new InvalidSmsConfigurationException('rahyabir config not found: api base url');
        }

        if (empty($this->username)) {
            throw new InvalidSmsConfigurationException('rahyabir config not found: username');
        }

        if (empty($this->password)) {
            throw new InvalidSmsConfigurationException('rahyabir config not found: password');
        }

        if (empty($this->company)) {
            throw new InvalidSmsConfigurationException('rahyabir config not found: company');
        }

        if (empty($this->number)) {
            throw new InvalidSmsConfigurationException('rahyabir config not found: from number');
        }
    }

    private function isResponseValid(array $response): bool
    {
        return isset($response[0]['submitResponse']) && isset($response[0]['submitResponse']);
    }

    private function getLoginApiUrl(): string
    {
        return $this->baseUrl.'/api/Auth/getToken';
    }

    private function getSendSmsApiUrl(): string
    {
        return $this->baseUrl.'/api/v1/SendSMS_Batch';
    }

    private function getTokenValidDay()
    {
        return $this->settings['token_valid_day'];
    }

    private function getHttpHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }
}
