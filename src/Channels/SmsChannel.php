<?php

namespace Tzsk\Sms\Channels;

use Tzsk\Sms\SmsManager;
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

        // Validate the message.
        $this->validate($message);

        $manager = new SmsManager;

        // Use custom driver if exists.
        if (! empty($message['driver'])) {
            $manager->withDriver($message['driver']);
        }

        // Send notification.
        return $manager->send($message['body'], function ($sms) use ($message) {
            $sms->to($message['recipients']);
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
        if (empty($message['body'])) {
            throw new \Exception('Message body could not be empty.');
        }

        if (empty($message['recipients'])) {
            throw new \Exception('Message recipient could not be empty.');
        }
    }
}
