#Repository design pattern Laravel

Foobar is a Python library for dealing with word pluralization.

## Installation

Use the package manager [Composer](https://packagist.org/packages/unlimited/repository-design-pattern-laravel) to install repository-design-pattern-laravel.

```bash
composer require unlimited/repository-design-pattern-laravel
```

## Config

add blow line to providers list in config/app.php
```bash
Unlimited\Repository\RepositoryServiceProvider::class,
```

## Usage

```php
php artisan repository:create RepositoryName
```

u can use --resource 
```php
php artisan repository:create RepositoryName --resource
```

## License
[MIT](https://choosealicense.com/licenses/mit/)