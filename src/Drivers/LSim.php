<?php

namespace Tzsk\Sms\Drivers;

use Illuminate\Support\Facades\Http;
use Tzsk\Sms\Contracts\Driver;

class LSim extends Driver
{
    public function send()
    {
        $responses = [];

        foreach ($this->recipients as $recipient) {
            $result = Http::withOptions(['verify' => false])
                ->get('http://apps.lsim.az/quicksms/v1/send', [
                    'login' => data_get($this->settings, 'username'),
                    'msisdn' => $recipient,
                    'text' => $this->body,
                    'sender' => $this->sender,
                    'key' => $this->getKey($this->body, $recipient),
                ]);

            $responses[$recipient] = $result;
        }

        $responses = collect($responses);

        return (count($this->recipients) == 1) ? $responses->first() : $responses;
    }

    private function getKey(string $message, string $number): string
    {
        return md5(
            md5(data_get($this->settings, 'password'))
            .data_get($this->settings, 'username')
            .$message
            .$number
            .$this->sender
        );
    }
}
