# A CRUD Generator Package For Laravel

## Requirements

- PHP >=5.4

## Installation

Require this package with composer:

```
composer require khodja/crud
```

Register the provider directly in your app configuration file config/app.php
```php
'providers' => [
    // ...
    Khodja\Crud\CrudServiceProvider::class, 
];
```

## Code example

Usage inside a laravel route
```bash
php artisan make:crud product --route=backend
```


## Support

Feel free to post your issues in the issues section.

## Security

If you discover any security related issues, please email fayzulla@khodja.uz instead of using the issue tracker.

## License

This library is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).