<?php

namespace Tzsk\Sms\Tests\Drivers;

use Tzsk\Sms\Facades\Sms;
use Tzsk\Sms\Tests\TestCase;
use Tzsk\Sms\Tests\Mocks\Drivers\MockSmsApi;

class SmsApiTest extends TestCase
{
    use DriverCommon;

    protected function getDriver()
    {
        return new MockSmsApi();
    }

    public function test_it_wont_accept_invalid_parameters()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Tzsk\Sms\Drivers\SmsApi::with contains invalid options');
        Sms::via('smsapi')->send("this message", function ($sms) {
            $sms->to(['Number 1', 'Number 2'])->with(['invalid_parameter' => 'value', 'sid' => 1, 'sname' => 'Sender Name']);
        });
    }
}
