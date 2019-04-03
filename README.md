# Notification Driver por [Laravel Auditing](http://laravel-auditing.com) ^8.0

[![Latest Version on Packagist](https://img.shields.io/packagist/v/andreshg112/laravel-auditing-notifications.svg?style=flat-square)](https://packagist.org/packages/andreshg112/laravel-auditing-notifications)
[![Build Status](https://travis-ci.com/andreshg112/laravel-auditing-notifications.svg?branch=master)](https://travis-ci.com/andreshg112/laravel-auditing-notifications)
[![StyleCI](https://github.styleci.io/repos/178957162/shield?branch=master)](https://github.styleci.io/repos/178957162)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/andreshg112/laravel-auditing-notifications/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/andreshg112/laravel-auditing-notifications/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/andreshg112/laravel-auditing-notifications/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/andreshg112/laravel-auditing-notifications/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/andreshg112/laravel-auditing-notifications.svg?style=flat-square)](https://packagist.org/packages/andreshg112/laravel-auditing-notifications)

This package allows you to send notifications with audit data instead of saving it to an accessible database. By default, it sends notifications through AWS SNS using the package [lab123it/aws-sns](https://github.com/lab123it/aws-sns).

## Use case

If you have some microservices and want to have centralized auditing, this could be helpful.

## Installation

### Step 1

You can install the package via composer:

```bash
composer require andreshg112/laravel-auditing-notifications
```

> This package depends on [owen-it/laravel-auditing ^8.0](https://github.com/owen-it/laravel-auditing) and [lab123it/aws-sns](https://github.com/lab123it/aws-sns), so you have to configure them first in order to make this work.

### Step 2

Change the audit default driver:

```php
return [
    // ...
    /*
    |--------------------------------------------------------------------------
    | Audit Driver
    |--------------------------------------------------------------------------
    |
    | The default audit driver used to keep track of changes.
    |
    */

    'driver' => Andreshg112\LaravelAuditingNotifications\NotificationDriver::class,
];
```

> You can make this locally on a model. Please see the documentation: http://laravel-auditing.com/docs/8.0/audit-drivers.

### Step 3

In your `config/audit.php` file, add this:

```php
return [
    // ...

    // andreshg112/laravel-auditing-notifications

    'notification-driver' => [
        // Required if you're going to use different notifications channels for sending audit data.
        'notifications' => [
            Andreshg112\LaravelAuditingNotifications\AuditSns::class,
        ],

        // Required if you're going to use the default Andreshg112\LaravelAuditingNotifications\AuditSns Notification.
        'sns' => [
            'topic_arn' => 'arn:aws:sns:us-east-1:xxxxxxxxxxxx:auditing-notifications',
        ],
    ],
];
```

## Usage

Add the `Illuminate\Notifications\Notifiable` trait to the models you want to audit, besides Auditable contract and trait.

```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Vehicle extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use \OwenIt\Auditing\Auditable, Notifiable;
}
```

That's all! Just execute an [auditable event](http://laravel-auditing.com/docs/8.0/audit-events) over the model in order to send a notification.

### Testing

```bash
composer test
```

### Changelog

Please see [Releases](../../releases) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email andreshg112@gmail.com instead of using the issue tracker.

## Credits

-   [Andrés Herrera García](https://github.com/andreshg112)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
