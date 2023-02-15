<?php

namespace Tzsk\Sms\Drivers;

use Illuminate\Support\Facades\Http;
use Tzsk\Sms\Contracts\Driver;
use Tzsk\Sms\Exceptions\InvalidMessageException;
use Tzsk\Sms\Exceptions\InvalidSmsConfigurationException;

class D7networks extends Driver
{
    private ?string $baseUrl;

    private ?string $username;

    private ?string $password;

    private ?string $token;

    protected function boot(): void
    {
        $this->baseUrl = trim($this->settings['url'], '/');
        $this->username = $this->settings['username'];
        $this->password = $this->settings['password'];
        $this->token = cache('d7networks_token');
    }

    public function send()
    {
        if (empty($this->token)) {
            $this->login();
        }

        if (count($this->recipients) > 200) {
            throw new InvalidMessageException('Recipients cannot be more than 100 numbers.');
        }

        $response = Http::withToken($this->token)->withHeaders($this->getHttpHeaders())
            ->post($this->getSendSmsApiUrl(), [
                'messages' => [
                    'channel' => 'sms',
                    'recipients' => $this->recipients,
                    'content' => $this->body,
                    'msg_type' => 'text',
                    'data_coding' => $this->getDataCoding($this->body),
                ],
                'message_globals' => [
                    'originator' => data_get($this->settings, 'originator'),
                    'report_url' => data_get($this->settings, 'report_url'),
                ],
            ]);

        $response = $response->json();

        if (isset($response['detail']['code'])) {
            cache()->forget('rahyabir_token');

            return $this->send();
        }

        if (! isset($response['detail'])) {
            return $response;
        }

        throw new InvalidMessageException(json_encode($response));
    }

    private function login(): void
    {
        $this->validateConfiguration();

        $response = Http::post($this->getLoginApiUrl(), [
            'client_id' => $this->username,
            'client_secret' => $this->password,
        ]);

        $response = $response->json();

        if (isset($response['detail'])) {
            $error = $response['detail'];

            throw new InvalidMessageException($error['message'], $error['code']);
        }

        $token = $response['access_token'];

        cache([
            'd7networks_token' => $token,
        ], now()->addDays($this->getTokenValidDay()));

        $this->token = $token;
    }

    private function validateConfiguration(): void
    {
        if (empty($this->baseUrl)) {
            throw new InvalidSmsConfigurationException('d7networks config not found: api base url');
        }

        if (empty($this->username)) {
            throw new InvalidSmsConfigurationException('d7networks config not found: username');
        }

        if (empty($this->password)) {
            throw new InvalidSmsConfigurationException('d7networks config not found: password');
        }

        if (empty($this->number)) {
            throw new InvalidSmsConfigurationException('d7networks config not found: from number');
        }
    }

    private function getErrorMessage(array $errors)
    {
        return array_shift($errors)['message'];
    }

    private function getLoginApiUrl(): string
    {
        return $this->baseUrl.'/auth/v1/login/application';
    }

    private function getSendSmsApiUrl(): string
    {
        return $this->baseUrl.'/messages/v1/send';
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

    private function getDataCoding(string $string): string
    {
        return strlen($string) != strlen(utf8_decode($string)) ? 'text' : 'auto';
    }
}
