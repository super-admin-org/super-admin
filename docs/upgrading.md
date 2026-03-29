# Upgrading

## Updating the Package

```bash
composer update super-admin-org/super-admin
```

After updating, republish the assets:

```bash
php artisan vendor:publish --tag=super-admin-assets --force
```

## Version Compatibility

| Super Admin | Laravel | PHP |
|-------------|---------|-----|
| 1.0.x | 10.x / 11.x / 12.x / 13.x | >= 8.2 |

## Upgrading from Open-Admin or Laravel-Admin

Super Admin is a fork of Open-Admin (itself a fork of Laravel-Admin). To migrate:

1. Update your `composer.json` to use `super-admin-org/super-admin`
2. Find and replace namespaces:
   - `OpenAdmin\Admin` to `SuperAdmin\Admin`
   - or `Encore\Admin` to `SuperAdmin\Admin`
3. Republish assets and config
4. Clear all caches

```bash
php artisan vendor:publish --provider="SuperAdmin\Admin\AdminServiceProvider" --force
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## Upgrading Laravel

When upgrading your Laravel version, check the [Laravel upgrade guide](https://laravel.com/docs/upgrade) for breaking changes. Super Admin supports Laravel 10 through 13 simultaneously.
