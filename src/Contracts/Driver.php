<?php

namespace Tzsk\Sms\Contracts;

use Tzsk\Sms\Exceptions\InvalidMessageException;

abstract class Driver
{
    protected array $recipients = [];

    protected string $body = '';

    abstract public function __construct(array $settings);

    public function to($numbers): Driver
    {
        $recipients = is_array($numbers) ? $numbers : [$numbers];

        $recipients = array_map(static function ($item) {
            return trim($item);
        }, array_merge($this->recipients, $recipients));

        $this->recipients = array_values(array_filter($recipients));

        if (count($this->recipients) < 1) {
            throw new InvalidMessageException('Message recipients cannot be empty.');
        }

        return $this;
    }

    public function message(string $message): Driver
    {
        $message = trim($message);

        if ($message === '') {
            throw new InvalidMessageException('Message text cannot be empty.');
        }

        $this->body = $message;

        return $this;
    }

    abstract public function send();
}
