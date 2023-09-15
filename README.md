# :gift: Laravel SMS Gateway

![SMS Cover](resources/sms.svg)

![GitHub License](https://img.shields.io/github/license/tzsk/sms?style=for-the-badge)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/tzsk/sms.svg?style=for-the-badge&logo=composer)](https://packagist.org/packages/tzsk/sms)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/tzsk/sms/tests.yml?branch=master&label=tests&style=for-the-badge&logo=github)](https://github.com/tzsk/sms/actions?query=workflow%3ATests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/tzsk/sms.svg?style=for-the-badge&logo=laravel)](https://packagist.org/packages/tzsk/sms)

This is a Laravel Package for SMS Gateway Integration. Now Sending SMS is easy.

List of supported gateways:

-   [AWS SNS](https://aws.amazon.com/sns/)
-   [Textlocal](http://textlocal.in)
-   [Twilio](https://www.twilio.com)
-   [Clockwork](https://www.clockworksms.com/)
-   [LINK Mobility](https://www.linkmobility.com)
-   [Kavenegar](https://kavenegar.com)
-   [Melipayamak](https://www.melipayamak.com)
-   [Melipayamakpattern](https://www.melipayamak.com)
-   [Smsir](https://www.sms.ir)
-   [Tsms](http://www.tsms.ir)
-   [Farazsms](https://farazsms.com)
-   [Farazsmspattern](https://farazsms.com)
-   [SMS Gateway Me](https://smsgateway.me)
-   [SmsGateWay24](https://smsgateway24.com/en)
-   [Ghasedak](https://ghasedaksms.com/)
-   [Sms77](https://www.sms77.io)
-   [SabaPayamak](https://sabapayamak.com)
-   [LSim](https://sendsms.az/)
-   [Rahyabcp](https://rahyabcp.ir/)
-   [Rahyabir](https://sms.rahyab.ir/)
-   [D7networks](https://d7networks.com/)
-   [Hamyarsms](https://hamyarsms.com/)
-   [SMSApi](https://www.smsapi.si/)

-   Others are under way.

## :package: Install

Via Composer

```bash
$ composer require tzsk/sms
```

## :zap: Configure

Publish the config file

```bash
$ php artisan sms:publish
```

In the config file you can set the default driver to use for all your SMS. But you can also change the driver at
runtime.

Choose what gateway you would like to use for your application. Then make that as default driver so that you don't have
to specify that everywhere. But, you can also use multiple gateways in a project.

```php
// Eg. if you want to use SNS.
'default' => 'sns',
```

Then fill the credentials for that gateway in the drivers array.

```php
// Eg. for SNS.
'drivers' => [
    'sns' => [
        // Fill all the credentials here.
        'key' => 'Your AWS SNS Access Key',
        'secret' => 'Your AWS SNS Secret Key',
        'region' => 'Your AWS SNS Region',
        'from' => 'Your AWS SNS Sender ID', //sender
        'type' => 'Tansactional', // Or: 'Promotional'
    ],
    ...
]
```

#### Textlocal Configuration:

Textlocal is added by default. You just have to change the creadentials in the `textlocal` driver section.

#### AWS SNS Configuration:

In case you want to use AWS SNS. Then you have to pull a composer library first.

```bash
composer require aws/aws-sdk-php
```

#### Clockwork Configuration:

In case you want to use Clockwork. Then you have to pull a composer library first.

```bash
composer require mediaburst/clockworksms
```

#### Twilio Configuration:

In case you want to use Twilio. Then you have to pull a composer library first.

```bash
composer require twilio/sdk
```

Then you just have to change the creadentials in the `twilio` driver section.

#### Melipayamak or Melipayamakpattern Configuration:

In case you want to use Melipayamak or Melipayamakpattern, Then you have to pull a composer library first.

```bash
composer require melipayamak/php
```

#### Kavenegar Configuration:

In case you want to use Kavenegar. Then you have to pull a composer library first.

```bash
composer require kavenegar/php
```

#### SMS Gateway Me Configuration:

In case you want to use SMS Gateway Me. Then you have to pull a composer library first.

```bash
composer require smsgatewayme/client
```

## :fire: Usage

In your code just use it like this.

```php
# On the top of the file.
use Tzsk\Sms\Facades\Sms;

////

# In your Controller.
Sms::send("this message", function($sms) {
    $sms->to(['Number 1', 'Number 2']); # The numbers to send to.
});
# OR...
Sms::send("this message")->to(['Number 1', 'Number 2'])->dispatch();

# If you want to use a different driver.
Sms::via('gateway')->send("this message", function($sms) {
    $sms->to(['Number 1', 'Number 2']);
});
# OR...
Sms::via('gateway')->send("this message")->to(['Number 1', 'Number 2'])->dispatch();

# Here gateway is explicit : 'twilio' or 'textlocal' or any other driver in the config.
# The numbers can be a single string as well.

# If you are not a Laravel's facade fan, you can use sms helper:

sms()->send("this message", function($sms) {
    $sms->to(['Number 1', 'Number 2']); # The numbers to send to.
});

sms()->send("this message")->to(['Number 1', 'Number 2'])->dispatch();

sms()->via('gateway')->send("this message", function($sms) {
    $sms->to(['Number 1', 'Number 2']);
});

sms()->via('gateway')->send("this message")->to(['Number 1', 'Number 2'])->dispatch();

# Change the from|sender|sim value with from() option:

sms()->via('gateway')->send("this message")->from('Your From Number | Sender Value | Sim Value ')->to(['Number 1', 'Number 2'])->dispatch();

# Sending argument and pattern code in pattern drivers such as melipayamakpattern and farazsmspattern.

#Note: The first argument is always known as the pattern code.

sms()->via('melipayamakpattern')->send("patterncode=123 \n arg1=name \n arg2=family")->to(['Number 1', 'Number 2'])->dispatch();

```

## :heart_eyes: Channel Usage

First you have to create your notification using `php artisan make:notification` command. then `SmsChannel::class` can
be used as channel like the below:

```php
namespace App\Notifications;

use Tzsk\Sms\Builder;
use Illuminate\Bus\Queueable;
use Tzsk\Sms\Channels\SmsChannel;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvoicePaid extends Notification
{
    use Queueable;

    /**
     * Get the notification channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return [SmsChannel::class];
    }

    /**
     * Get the repicients and body of the notification.
     *
     * @param  mixed  $notifiable
     * @return Builder
     */
    public function toSms($notifiable)
    {
        return (new Builder)->via('gateway') # via() is Optional
            ->send('this message')
            ->to('some number');
    }
}
```

> **Tip:** You can use the same Builder Instance in the send method.

```php
$builder = (new Builder)->via('gateway') # via() is Optional
    ->send('this message')
    ->to('some number');

Sms::send($builder);

# OR...
$builder = (new Builder)->send('this message')
    ->to(['some number']);

Sms::via('gateway')->send($builder);
```

#### Custom Made Driver, How To:

First you have to name your driver in the drivers array ,and also specify any config params you want.

```php
'drivers' => [
    'textlocal' => [...],
    'twilio' => [...],
    'my_driver' => [
        ... # Your Config Params here.
    ]
]
```

Now you have to create a Driver Map Class that will be used to send the SMS. In your driver, You just have to
extend `Tzsk\Sms\Contracts\Driver`.

Ex. You created a class : `App\Packages\SMSDriver\MyDriver`.

```php

namespace App\Packages\SMSDriver;

use Tzsk\Sms\Contracts\Driver;

class MyDriver extends Driver
{
    /**
    * You Should implement these methods:
    *
    * 1. boot() -> (optional) Initialize any variable or configuration that you need.
    * 2. send() -> Main method to send messages.
    *
    * Note: settings array will be automatically assigned in Driver class' constructor.
    *
    * Example Given below:
    */

    /**
    * @var mixed
    */
    protected $client;

    protected function boot() : void
    {
        $this->client = new Client(); # Guzzle Client for example.
    }

    /**
    * @return object Ex.: (object) ['status' => true, 'data' => 'Client Response Data'];
    */
    public function send()
    {
        $this->recipients; # Array of Recipients.
        $this->body; # SMS Body.

        # Main logic of Sending SMS.
        ...
    }

}
```

Once you crate that class you have to specify it in the `sms.php` Config file `map` section.

```php
'map' => [
    ...
    'my_driver' => App\Packages\SMSDriver\MyDriver::class,
]
```

**Note:-** You have to make sure that the key of the `map` array is identical to the key of the `drivers` array.

## :microscope: Testing

```bash
composer test
```

## :date: Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## :heart: Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## :lock: Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## :crown: Credits

-   [Kazi Ahmed](https://github.com/tzsk)
-   [All Contributors](../../contributors)

## :policeman: License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
