<?php

namespace Tzsk\Sms\Channels;

use Exception;
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
        $conditions = [
            'Invalid data for sms notification.' => ! is_a($message, SmsBuilder::class),
            'Message body could not be empty.' => empty($message->getBody()),
            'Message recipient could not be empty.' => empty($message->getRecipients()),
        ];

        foreach ($conditions as $ex => $condition) {
            throw_if($condition, new Exception($ex));
        }
    }
}
