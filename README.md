# Laravel SMS Gateway

[![Software License][ico-license]](LICENSE.md)
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![StyleCI](https://styleci.io/repos/76770163/shield?branch=master)](https://styleci.io/repos/76770163)
[![Build Status](https://scrutinizer-ci.com/g/tzsk/sms/badges/build.png?b=master)](https://scrutinizer-ci.com/g/tzsk/sms/build-status/master)
[![Code Climate](https://codeclimate.com/github/tzsk/payu/badges/gpa.svg)](https://codeclimate.com/github/tzsk/sms)
[![Quality Score][ico-code-quality]][link-code-quality]

This is a Laravel 5 Package for SMS Gateway Integration. This package supports `Laravel 5.2 or Higher`. Now Sending SMS is easy.

List of supported gateways:
- [Textlocal](http://textlocal.in)
- [Twilio](https://www.twilio.com)
- [LINK Mobility](https://www.linkmobility.com)
- Others are under way.

Older version support (Laravel 5.1) is coming soon.

## Install

Via Composer

``` bash
$ composer require tzsk/sms
```

## Configure
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

#### Textlocal Configuration:

Textlocal is added by default. You just have to change the creadentials in the `textlocal` driver section.

#### Twilio Configuratoin:

In case you want to use Twilio. Then you have to pull a composer library first.

```bash
composer require twilio/sdk
```

Then you just have to change the creadentials in the `twilio` driver section.

## Usage

In your code just use it like this.
``` php
# On the top of the file.
use Tzsk\Sms\Facade\Sms;

...

# In your Controller.
Sms::send("Text to send.", function($sms) {
    $sms->to(['Number 1', 'Number 2']); # The numbers to send to.
});

# If you want to use a different driver.
Sms::with('driver name')->send("Text to send.", function($sms) {
    $sms->to(['Number 1', 'Number 2']);
});

# Here driver name is explicit : 'twilio' or 'textlocal'.
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

Now you have to create a Driver Map Class that will be used to send the SMS. In your driver, You just have to extend `Tzsk\Sms\Drivers\MasterDriver`.

Ex. You careated a class : `App\Packages\SMSDriver\MyDriver`.

```php
namespace App\Packages\SMSDriver;

use Tzsk\Sms\Drivers\MasterDriver;

class MyDriver extends MasterDriver 
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
