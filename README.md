# Laravel SMS Gateway

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

This is a Laravel 5 Package for SMS Gateway Integration. This package supports `Laravel 5.2 or Higher`. Now Sending SMS is easy.

List of supported gateways:
- [Textlocal](http://textlocal.in)
- Others area under way.

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
Change Config file with your creadentials.

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
```

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
