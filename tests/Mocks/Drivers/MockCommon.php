<?php

namespace Tzsk\Sms\Tests\Mocks\Drivers;

trait MockCommon
{
    public function getRecipients()
    {
        return $this->recipients;
    }

    public function getBody()
    {
        return $this->body;
    }
}
