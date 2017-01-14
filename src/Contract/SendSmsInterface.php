<?php
namespace Tzsk\Sms\Contract;

/**
 * Interface SendSmsInterface
 * @package Tzsk\Sms\Contract
 */
interface SendSmsInterface
{
    /**
     * Construct the class with the relevant settings.
     *
     * SendSmsInterface constructor.
     * @param $settings object
     */
    public function __construct($settings);

    /**
     * String or Array of numbers.
     *
     * @param $numbers string|array
     * @return mixed
     */
    public function to($numbers);

    /**
     * Set text message body.
     *
     * @param $message string
     * @return mixed
     */
    public function message($message);

    /**
     * Send text message and return response.
     *
     * @param $message
     * @return mixed
     */
    public function send();

}