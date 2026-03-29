# Extension Development

Super Admin supports a plugin/extension system for adding features.

## Creating an Extension

```bash
php artisan admin:extend my-extension
```

This generates a scaffold in the extension directory.

## Extension Structure

```
my-extension/
├── src/
│   └── MyExtensionServiceProvider.php
├── resources/
│   └── views/
├── routes/
│   └── web.php
├── database/
│   └── migrations/
├── public/
│   └── assets/
├── composer.json
└── README.md
```

## Registering an Extension

In `app/Admin/bootstrap.php`:

```php
use App\Admin\Extensions\MyExtension;

Admin::extend('my-extension', MyExtension::class);
```

## Installing Extensions

Most extensions follow this pattern:

```bash
# Install via Composer
composer require super-admin-ext/extension-name

# Import into admin
php artisan admin:import extension-name
```

## Available Official Extensions

| Extension | Install |
|-----------|---------|
| Helpers | `composer require super-admin-ext/helpers` |
| Media Manager | `composer require super-admin-ext/media-manager` |
| Config Manager | `composer require super-admin-ext/config` |
| Grid Sortable | `composer require super-admin-ext/grid-sortable` |
| CKEditor | `composer require super-admin-ext/ckeditor` |
| API Tester | `composer require super-admin-ext/api-tester` |
| Scheduling | `composer require super-admin-ext/scheduling` |
| PHPInfo | `composer require super-admin-ext/phpinfo` |
| Log Viewer | `composer require super-admin-ext/log-viewer` |
| Page Designer | `composer require super-admin-ext/page-designer` |
| Reporter | `composer require super-admin-ext/reporter` |
| Redis Manager | `composer require super-admin-ext/redis-manager` |

After installing, import the extension:

```bash
php artisan admin:import {extension-name}
```

## Custom Service Provider

```php
<?php

namespace App\Admin\Extensions;

use SuperAdmin\Admin\Extension;

class MyExtension extends Extension
{
    public $name = 'my-extension';

    public $views = __DIR__ . '/../resources/views';

    public $assets = __DIR__ . '/../public';

    public $menu = [
        'title' => 'My Extension',
        'path'  => 'my-extension',
        'icon'  => 'fa-puzzle-piece',
    ];
}
```
