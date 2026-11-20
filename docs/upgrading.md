# Upgrading

## Updating the Package

Update Super Admin and its extensions together:

```bash
composer update "super-admin-org/*"
```

This stays inside the versions your `composer.json` allows, for example 2.0 to a newer 2.x. To move from 1.x to 2.x, follow [Upgrading from 1.x to 2.x](#upgrading-from-1x-to-2x).

Then finish the update with one command. It publishes the new assets, runs the new Super Admin and extension migrations, and clears the compiled views:

```bash
php artisan admin:upgrade
```

| Option | Effect |
|--------|--------|
| `--force` | Run the migrations in production without asking |
| `--skip-assets` | Do not publish the assets |
| `--skip-migrations` | Do not run the migrations |

### What happens automatically

- **Assets.** Super Admin and its extensions tag their assets `laravel-assets`. Laravel apps already run `php artisan vendor:publish --tag=laravel-assets --force` after every `composer update` (the `post-update-cmd` script in `composer.json`), so new CSS and JavaScript arrive without a manual step.
- **Migrations.** The package migrations are loaded from the package, so `php artisan migrate` runs new ones without publishing them first. Copies that older versions published to `database/migrations` keep working, and no migration runs twice. To publish and edit the migrations yourself, set `'load_migrations' => false` under `database` in `config/admin.php`.
- **Reminder.** While the published assets are older than the package, or migrations have not run, administrators see a "Super Admin was updated" notice at the top of every page. Hide it with `'upgrade_notice' => false` in `config/admin.php`.

### Run it after every update

Add `admin:upgrade` to the `post-update-cmd` scripts of your application's `composer.json`:

```json
"scripts": {
    "post-update-cmd": [
        "@php artisan vendor:publish --tag=laravel-assets --ansi --force",
        "@php artisan admin:upgrade --no-interaction"
    ]
}
```

> **Production databases:** With `--no-interaction`, `admin:upgrade` does **not** migrate a production database (`APP_ENV=production`); it prints the pending migrations instead. Review them, back up the database, then run `php artisan admin:upgrade --force` (or `php artisan migrate --force`) as part of your deployment.

## Upgrading from 1.x to 2.x

`composer update super-admin-org/super-admin` does **not** install 2.x. Your `composer.json` asks for `^1.x` (for example `^1.2`), which means "1.2 or newer, but below 2.0", and Composer never crosses a major version on its own. You change the requirement, then update Super Admin and its extensions in one step.

**Before you start:** back up the database and commit your code. 2.x needs PHP 8.0+ and Laravel 8 to 13, which every 1.x installation already meets (1.x needed PHP 8.2 and Laravel 10+).

### Step 1. Require 2.x

```bash
composer require super-admin-org/super-admin:~2.0 --no-update
```

This only changes the line in `composer.json`; nothing is installed yet. `~2.0` means any 2.x version, the same as `^2.0`. Check that `composer.json` now says `"super-admin-org/super-admin": "~2.0"`.

> **Windows: do not type ^2.0.** In PowerShell and cmd, Windows removes the `^` from `^2.0`, even inside quotes. Composer then writes the exact version `"2.0"` and warns *The "2.0" constraint for "super-admin-org/super-admin" appears too strict*. If you saw that warning, run the command above again; it replaces the line.


### Step 2. Update Super Admin and the extensions together

Run this only after Step 1. Before it, Composer prints *Nothing to modify in lock file* and stays on 1.x.

```bash
composer update "super-admin-org/*" --with-all-dependencies
```

When it works, the output contains `Upgrading super-admin-org/super-admin (v1.2.1 => v2.0.1)`.

The extensions must move in the same step: their older releases only accept Super Admin 1.x. These are the first releases that accept 2.x:

| Extension | Works with 2.x from |
|-----------|---------------------|
| api-tester | v1.0.6 |
| ckeditor | v1.0.7 |
| config | v1.0.5 |
| grid-sortable | v1.0.5 |
| helpers | v1.18.3 |
| log-viewer | v1.3.3 |
| media-manager | v1.0.4 |
| page-designer | v1.0.6 |
| phpinfo | v1.0.1 |
| redis-manager | v1.0.5 |
| reporter | v1.0.6 |
| scheduling | v1.0.5 |

If Composer stops with an error like this:

```
- super-admin-org/log-viewer v1.3.2 requires super-admin-org/super-admin ^1.0
  -> ... but it conflicts with your root composer.json require (^2.0).
```

that extension is pinned to an old version in your `composer.json` (for example `"super-admin-org/log-viewer": "1.3.2"`). Change it to a range such as `"^1.3"` and run Step 2 again.

> **Why not one command?** `composer require super-admin-org/super-admin:~2.0 -W` fails when the extensions are locked to releases that only accept 1.x ("an update of this package was not requested"). Updating `super-admin-org/*` lets Composer move the core and every extension at once.

### Step 3. Finish the update

```bash
php artisan admin:upgrade
```

It publishes the 2.x assets, runs the new migrations (the `admin_settings` table and the two-factor columns on `admin_users`) and clears the compiled views. On a production server add `--force`, or it only lists the migrations.

### Step 4. Choose the look

2.x has two templates: **Tailwind Light** (the default) and **Bootstrap Classic**, which is how 1.x looked. To keep the 1.x look, add this to `config/admin.php`:

```php
'template' => 'bootstrap-classic',
```

or switch at any time in **Admin > Templates**. Existing installations do not have that menu item yet; add it, with its permission path, with:

```bash
php artisan admin:template --menu
```

See [Templates](templates.md).

### Step 5. Check what you customised

- **`config/admin.php`**: keep your file. The new settings (`template`, `template_switcher`, `two_factor`, `upgrade_notice`, and `settings_table`, `settings_model`, `load_migrations` under `database`) have defaults. Copy a section from the package's `config/admin.php` only when you want to change it. Do not run `vendor:publish --tag=super-admin-config --force`: it replaces your file.
- **Published views** in `resources/views/vendor/admin/` now apply to every template. Views copied from 1.x contain Bootstrap markup; move them to `resources/views/vendor/admin/bootstrap-classic/` so they only change that template, and delete the ones you never edited.
- **A custom `AuthController`** that overrides `postLogin()` skips the two-factor check. Call `parent::postLogin($request)` from it, or keep two-factor off.
- **Published language files** from 1.x lack the new texts; Super Admin shows English for those until you add them.

### Step 6. Check the panel

Log in, open a few pages and **Admin > Templates**. The "Super Admin was updated" notice disappears once Step 3 has run. Two-factor login is available but changes nothing until a user turns it on (user menu > **Two-factor**); the options are listed in the [Changelog](../CHANGELOG.md).

## Version Compatibility

| Super Admin | Laravel | PHP | Pest |
|-------------|---------|-----|------|
| 2.x | 8.x / 9.x / 10.x / 11.x / 12.x / 13.x | >= 8.0 | 1.x / 2.x / 3.x / 4.x |
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

When upgrading your Laravel version, check the [Laravel upgrade guide](https://laravel.com/docs/upgrade) for breaking changes. Super Admin 2.x supports Laravel 8 through 13 simultaneously.
