# OhioCMS User Package

## Migrations / Seeds / Factories

```sudo composer dumpautoload
```

```php artisan vendor:publish --provider="Ohio\Core\Base\OhioCoreServiceProvider" --force
```

```php artisan cache:clear;sudo service php7.0-fpm restart;
``` 

```php artisan migrate:refresh --seed #re-run all migrations with seeds
```

## Testing

```phpunit -c vendor/ohiocms/core/base --coverage-html=html
```