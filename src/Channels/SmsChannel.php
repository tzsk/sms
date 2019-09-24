<?php

namespace Tzsk\Sms\Channels;

use Tzsk\Sms\SmsBuilder;
use Illuminate\Notifications\Notification;

class SmsChannel
{
    /**
     * Send the given notification.
     *
     * @param $notifiable
     * @param Notification $notification
     * @return mixed
     * @throws \Exception
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSms($notifiable);

        $this->validate($message);
        $manager = app()->make('tzsk-sms');

        if (! empty($message->getDriver())) {
            $manager->via($message->getDriver());
        }

        return $manager->send($message->getBody(), function ($sms) use ($message) {
            $sms->to($message->getRecipients());
        });
    }

    /**
     * Validate message.
     *
     * @param $message
     * @throws \Exception
     */
    private function validate($message)
    {
        if (! is_a($message, SmsBuilder::class)) {
            throw new \Exception('Invalid data for sms notification.');
        }

        if (empty($message->getBody())) {
            throw new \Exception('Message body could not be empty.');
        }

        if (empty($message->getRecipients())) {
            throw new \Exception('Message recipient could not be empty.');
        }
    }
}
