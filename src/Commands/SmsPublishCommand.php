<?php

namespace Tzsk\Sms\Commands;

use Illuminate\Console\Command;

class SmsPublishCommand extends Command
{
    public $signature = 'sms:publish';

    public $description = 'Publish sms config file';

    public function handle()
    {
        $this->call('vendor:publish', ['--tag' => 'sms-config']);
    }
}
