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

| Super Admin | Laravel | PHP | Pest |
|-------------|---------|-----|------|
| 1.2.x | 10.x / 11.x / 12.x / 13.x | >= 8.2 | 2.x / 3.x / 4.x |

### Dev Dependency Matrix

Due to transitive dependency constraints, the test runner version depends on your Laravel version:

| Laravel | Pest | PHPUnit | nunomaduro/collision |
|---------|------|---------|---------------------|
| 10.x | 2.x | 10.x | ^7.0 |
| 11.x | 3.x | 11.x | ^8.0 |
| 12.x | 3.x / 4.x | 11.x / 12.x | ^8.0 |
| 13.x | 4.x | 12.x | ^8.9 |

Composer resolves the correct versions automatically.

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
