# Track users who manage models in your Laravel app

[![Latest Version on Packagist](https://img.shields.io/packagist/v/signifly/laravel-manageable.svg?style=flat-square)](https://packagist.org/packages/signifly/laravel-manageable)
[![Build Status](https://img.shields.io/travis/signifly/laravel-manageable/master.svg?style=flat-square)](https://travis-ci.org/signifly/laravel-manageable)
[![StyleCI](https://styleci.io/repos/119214202/shield?branch=master)](https://styleci.io/repos/119214202)
[![Quality Score](https://img.shields.io/scrutinizer/g/signifly/laravel-manageable.svg?style=flat-square)](https://scrutinizer-ci.com/g/signifly/laravel-manageable)
[![Total Downloads](https://img.shields.io/packagist/dt/signifly/laravel-manageable.svg?style=flat-square)](https://packagist.org/packages/signifly/laravel-manageable)

The `signifly/laravel-manageable` package allows you to easily track who creates/updates your models.

All you have to do to get started is:

```php
// 1. Add required columns to your table by using our macro manageable
Schema::create('orders', function (Blueprint $table) {
    // ...
    $table->manageable();

    // params: $bigIntegers (default: true), $foreignTable (default: 'users'), $foreignKey (default: 'id')
    $table->manageable(false, 'some_users_table', 'u_id');
});

// 2. Add the Manageable trait to your model
class Order extends Model
{
    use Manageable;
}
```

The macro `manageable` adds the following to your table:
```php
$this->unsignedBigInteger('created_by')->nullable()->index();
$this->unsignedBigInteger('updated_by')->nullable()->index();

$this->foreign('created_by')
    ->references('id')
    ->on('users')
    ->onDelete('cascade');

$this->foreign('updated_by')
    ->references('id')
    ->on('users')
    ->onDelete('cascade');
```

## Documentation
Until further documentation is provided, please have a look at the tests.

## Installation

You can install the package via composer:

```bash
composer require signifly/laravel-manageable
```

The package will automatically register itself.

You can publish the config with:
```bash
php artisan vendor:publish --provider="Signifly\Manageable\ManageableServiceProvider"
```

## Testing
```bash
composer test
```

## Security

If you discover any security issues, please email dev@signifly.com instead of using the issue tracker.

## Credits

- [Morten Poul Jensen](https://github.com/pactode)
- [All contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
