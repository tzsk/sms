<?php

namespace Tzsk\Sms\Abstracts;

use Tzsk\Sms\Contracts\DriverInterface;

abstract class Driver implements DriverInterface
{
    /**
     * To Numbers array.
     *
     * @var array
     */
    protected $recipients = [];

    /**
     * Message body.
     *
     * @var string
     */
    protected $body = '';

    /**
     * Driver constructor.
     *
     * @param $settings
     */
    abstract public function __construct($settings);

    /**
     * String or Array of numbers.
     *
     * @param $numbers string|array
     * @return $this
     * @throws \Exception
     */
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

    /**
     * Set text message body.
     *
     * @param $message string
     * @return $this
     * @throws \Exception
     */
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

    /**
     * Send the message
     *
     * @return object
     */
    abstract public function send();
}
