<?php

namespace Tzsk\Sms\Drivers;

use Illuminate\Support\Facades\Http;
use Tzsk\Sms\Contracts\Driver;
use Tzsk\Sms\Exceptions\InvalidMessageException;
use Tzsk\Sms\Exceptions\InvalidSmsConfigurationException;

class SabaPayamak extends Driver
{
    private ?string $baseUrl;

    private ?string $username;

    private ?string $password;

    private ?string $virtualNumber;

    private ?string $token;

    protected function boot(): void
    {
        $this->baseUrl = trim($this->settings['url'], '/');
        $this->username = $this->settings['username'];
        $this->password = $this->settings['password'];
        $this->virtualNumber = $this->sender;
        $this->token = cache('sabapayamak_token');
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
                'Text' => $this->body,
                'Numbers' => $this->recipients,
            ]);

        $jsonResponse = $response->json();

        if (empty($jsonResponse['errors'])) {
            return $jsonResponse;
        }

        $error = $this->getFirstError($jsonResponse['errors']);

        if ($error['code'] == 401) {
            cache()->forget('sabapayamak_token');

            return $this->send();
        }

        throw new InvalidMessageException($error['message'], $response->status());
    }

    private function login(): void
    {
        $this->validateConfiguration();

        $response = Http::withHeaders($this->getHttpHeaders())->post($this->getLoginApiUrl(), [
            'Username' => $this->username,
            'Password' => $this->password,
            'VirtualNumber' => $this->virtualNumber,
            'TokenValidDay' => $this->getTokenValidDay(),
        ]);

        $response = $response->json();

        if (! isset($response['data']['token'])) {
            $errors = $response['errors'];
            $errorMessage = $this->getErrorMessage($errors);

            throw new InvalidMessageException($errorMessage, $response['status']);
        }

        cache([
            'sabapayamak_token' => $response['data']['token'],
        ], now()->addDays($this->getTokenValidDay()));

        $this->token = $response['data']['token'];
    }

    private function validateConfiguration(): void
    {
        if (empty($this->baseUrl)) {
            throw new InvalidSmsConfigurationException('sabapayamak config not found: api base url');
        }

        if (empty($this->username)) {
            throw new InvalidSmsConfigurationException('sabapayamak config not found: username');
        }

        if (empty($this->password)) {
            throw new InvalidSmsConfigurationException('sabapayamak config not found: password');
        }

        if (empty($this->virtualNumber)) {
            throw new InvalidSmsConfigurationException('sabapayamak config not found: virtual number');
        }
    }

    private function getErrorMessage(array $errors)
    {
        return array_shift($errors)['message'];
    }

    private function getFirstError(array $errors)
    {
        return array_shift($errors);
    }

    private function getLoginApiUrl(): string
    {
        return $this->baseUrl.'/api/v1/user/authenticate';
    }

    private function getSendSmsApiUrl(): string
    {
        return $this->baseUrl.'/api/v1/message';
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
