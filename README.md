# Laravel SMS Gateway

[![Software License][ico-license]](LICENSE.md)
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![StyleCI](https://styleci.io/repos/76770163/shield?branch=master)](https://styleci.io/repos/76770163)
[![Build Status](https://travis-ci.org/tzsk/sms.svg?branch=master)](https://travis-ci.org/tzsk/sms)
[![Code Climate](https://codeclimate.com/github/tzsk/payu/badges/gpa.svg)](https://codeclimate.com/github/tzsk/sms)
[![Quality Score][ico-code-quality]][link-code-quality]

This is a Laravel Package for SMS Gateway Integration. Now Sending SMS is easy.

List of supported gateways:
- [AWS SNS](https://aws.amazon.com/sns/)
- [Nexmo](https://www.nexmo.com/)
- [Textlocal](http://textlocal.in)
- [Twilio](https://www.twilio.com)
- [Clockwork](https://www.clockworksms.com/)
- [LINK Mobility](https://www.linkmobility.com)
- [Kavenegar](https://kavenegar.com)
- [Melipayamak](https://www.melipayamak.com)
- [Smsir](https://www.sms.ir)
- [Tsms](http://www.tsms.ir)
- [Farazsms](https://farazsms.com)
- [SMS Gateway Me](https://smsgateway.me)
- Others are under way.

## Install

Via Composer

``` bash
$ composer require tzsk/sms
```

## Configure

If you are using `Laravel 5.5` or higher then you don't need to add the provider and alias.

In your `config/app.php` file add these two lines.

```php
# In your providers array.
'providers' => [
    ...
    Tzsk\Sms\Provider\SmsServiceProvider::class,
],

# In your aliases array.
'aliases' => [
    ...
    'Sms' => Tzsk\Sms\Facade\Sms::class,
],
```

Now run `php artisan vendor:publish` to publish `config/sms.php` file in your config directory.

In the config file you can set the default driver to use for all your SMS. But you can also change the driver at runtime.

Chose what gateway you would like to use for your application. Then make that as default driver so that you don't have to specify that everywhere. But, you can also use multiple gateways in a project.

```php
// Eg. if you wan to use SNS.
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
        'sender' => 'Your AWS SNS Sender ID',
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

#### Nexmo Configuration:

In case you want to use Nexmo. Then you have to pull a composer library first.

```bash
composer require nexmo/client
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

#### Melipayamak Configuration:

In case you want to use Melipayamak. Then you have to pull a composer library first.

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

## Usage

In your code just use it like this.
```php
# On the top of the file.
use Tzsk\Sms\Facade\Sms;

...

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

```

## Channel Usage

First you have to create your notification using `php artisan make:notification` command.
then `SmsChannel::class` can be used as channel like the below:

```php
namespace App\Notifications;

use Tzsk\Sms\SmsBuilder;
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
     * @return SmsBuilder
     */
    public function toSms($notifiable)
    {
        return (new SmsBuilder)->via('gateway') # via() is Optional
            ->send('this message')
            ->to('some number');
    }
}
```

> **Tip:** You can use the same Builder Instance in the send method.

```php
$builder = (new SmsBuilder)->via('gateway') # via() is Optional
    ->send('this message')
    ->to('some number');

Sms::send($builder);

# OR...
$builder = (new SmsBuilder)->send('this message')
    ->to(['some number']);

Sms::via('gateway')->send($builder);
```

#### Custom Made Driver, How To:

First you have to name your driver in the drivers array and also you can specify any config params you want.

```php
'drivers' => [
    'textlocal' => [...],
    'twilio' => [...],
    'my_driver' => [
        ... # Your Config Params here.
    ]
]
```

Now you have to create a Driver Map Class that will be used to send the SMS.
In your driver, You just have to extend `Tzsk\Sms\Abstracts\Driver`.

Ex. You created a class : `App\Packages\SMSDriver\MyDriver`.

```php
namespace App\Packages\SMSDriver;

use Tzsk\Sms\Abstracts\Driver;

class MyDriver extends Driver 
{
    # You will have to make 2 methods.
    /**
    * 1. __constructor($settings) # {Mandatory} This settings is your Config Params that you've set.
    * 2. send() # (Mandatory) This is the main message that will be sent.
    *
    * Example Given below:
    */

    /**
    * @var object
    */
    protected $settings;

    /**
    * @var mixed
    */
    protected $client;

    /**
    * Your Driver Config.
    *
    * @var array $settings
    */
    public function __construct($settings)
    {
        $this->settings = (object) $settings;
        # Initialize any Client that you want.
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

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email mailtokmahmed@gmail.com instead of using the issue tracker.

## Credits

- [Kazi Mainuddin Ahmed][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/tzsk/sms.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/tzsk/sms/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/tzsk/sms.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/tzsk/sms.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/tzsk/sms.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/tzsk/sms
[link-travis]: https://travis-ci.org/tzsk/sms
[link-scrutinizer]: https://scrutinizer-ci.com/g/tzsk/sms/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/tzsk/sms
[link-downloads]: https://packagist.org/packages/tzsk/sms
[link-author]: https://github.com/tzsk
[link-contributors]: ../../contributors
