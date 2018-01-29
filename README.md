# Track users who manages models in your Laravel app

The `signifly/laravel-manageable` package allows you to easily track who creates/updates your models.

All you have to do to get started is:

```php
// 1. Add required columns to your table by using our macro manageable
Schema::create('orders', function (Blueprint $table) {
    // ...
    $table->manageable();

    // if you want to change the name of the foreignTable for users and foreignKey
    $table->manageable('some_users_table', 'u_id');
});

// 2. Add the Manageable trait to your model
class Order extends Model
{
    use Manageable;
}
```

The macro `manageable` adds the following to your table:
```php
$this->unsignedInteger('created_by')->nullable()->index();
$this->unsignedInteger('updated_by')->nullable()->index();

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
$ composer require signifly/laravel-manageable
```

The package will automatically register itself.

You can publish the config with:
```bash
$ php artisan vendor:publish --provider="Signifly\Manageable\ManageableServiceProvider"
```

## Testing
```bash
$ composer test
```

## Security

If you discover any security issues, please email dev@signifly.com instead of using the issue tracker.

## Credits

- [Morten Poul Jensen](https://github.com/pactode)
- [All contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
