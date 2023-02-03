<?php

namespace Tzsk\Sms\Contracts;

use Tzsk\Sms\Exceptions\InvalidMessageException;

abstract class Driver
{
    protected array $settings = [];

    protected array $recipients = [];

    protected string $body = '';

    protected ?string $sender = '';

    public function __construct(array $settings)
    {
        $this->settings = $settings;
        $this->from(data_get($settings, 'from'));
        $this->boot();
    }

    public function to($numbers): self
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

    public function from(?string $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function message(string $message): self
    {
        $message = trim($message);

        if ($message === '') {
            throw new InvalidMessageException('Message text cannot be empty.');
        }

        $this->body = $message;

        return $this;
    }

    protected function boot(): void
    {
        //
    }

    abstract public function send();
}
