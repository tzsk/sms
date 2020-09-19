<?php

namespace Tzsk\Sms\Contracts;

abstract class Driver
{
    protected array $recipients = [];

    protected string $body = '';

    abstract public function __construct(array $settings);

    public function to($numbers)
    {
        $recipients = is_array($numbers) ? $numbers : [$numbers];
        $recipients = array_map(function ($item) {
            return trim($item);
        }, array_merge($this->recipients, $recipients));

        $this->recipients = array_values(array_filter($recipients));

        if (count($this->recipients) < 1) {
            throw new \Exception('Message recipient could not be empty.');
        }

        return $this;
    }

    public function message($message)
    {
        if (! is_string($message)) {
            throw new \Exception('Message text should be a string.');
        }
        if (trim($message) == '') {
            throw new \Exception('Message text could not be empty.');
        }
        $this->body = $message;

        return $this;
    }

    abstract public function send();
}
