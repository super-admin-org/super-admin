# Configuration

All configuration is stored in `config/admin.php`. Below is a reference of all available settings.

## Application Settings

```php
'name'  => 'Super Admin',        // Application name shown in header
'logo'  => 'Super Admin',        // Logo HTML or text
'logo-mini' => 'SA',             // Mini logo for collapsed sidebar
```

## Route Settings

```php
'route' => [
    'prefix'     => 'admin',                           // URL prefix
    'namespace'  => 'App\\Admin\\Controllers',          // Controller namespace
    'middleware' => ['web', 'admin'],                   // Route middleware
],
```

## Install Directory

```php
'directory' => app_path('Admin'),  // Where admin files are generated
```

## Authentication

```php
'auth' => [
    'controller' => App\Admin\Controllers\AuthController::class,

    'guard' => 'admin',

    'guards' => [
        'admin' => [
            'driver'   => 'session',
            'provider' => 'admin',
        ],
    ],

    'providers' => [
        'admin' => [
            'driver' => 'eloquent',
            'model'  => SuperAdmin\Admin\Auth\Database\Administrator::class,
        ],
    ],

    // Login throttling
    'throttle_attempts' => 5,
    'throttle_timeout'  => 60, // seconds

    // Remember me
    'remember' => true,

    // Redirect after login
    'redirect_to' => 'auth/login',

    // Routes without authentication
    'excepts' => [
        'auth/login',
        'auth/logout',
    ],
],
```

## Database Tables

```php
'database' => [
    'users_model'       => SuperAdmin\Admin\Auth\Database\Administrator::class,
    'users_table'       => 'admin_users',
    'roles_table'       => 'admin_roles',
    'permissions_table' => 'admin_permissions',
    'menu_table'        => 'admin_menu',
    'operation_log_table' => 'admin_operation_log',
    'user_permissions_table' => 'admin_user_permissions',
    'role_users_table'      => 'admin_role_users',
    'role_permissions_table' => 'admin_role_permissions',
    'role_menu_table'       => 'admin_role_menu',
],
```

## File Upload

```php
'upload' => [
    'disk' => 'admin',          // Filesystem disk name

    'directory' => [
        'image' => 'images',    // Image upload directory
        'file'  => 'files',     // File upload directory
    ],
],
```

Make sure to configure the corresponding disk in `config/filesystems.php`:

```php
'disks' => [
    'admin' => [
        'driver'     => 'local',
        'root'       => public_path('uploads'),
        'visibility' => 'public',
        'url'        => env('APP_URL') . '/uploads',
    ],
],
```

## Operation Log

```php
'operation_log' => [
    'enable'            => true,
    'allowed_methods'   => ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH'],
    'secret_fields'     => ['password', 'password_confirmation'],  // Fields to mask in logs
    'except'            => [],  // Routes to exclude from logging
],
```

## Layout & Appearance

```php
'skin'    => 'skin-blue-light',  // Theme skin
'layout'  => ['sidebar-mini'],   // Layout options

'show_environment' => true,      // Show environment tag in footer
'show_version'     => true,      // Show version in footer

'menu_search' => true,           // Enable menu search
'menu_bind_permission' => true,  // Bind menu visibility to permissions

'enable_default_breadcrumb' => true,
'enable_user_panel' => false,    // Show user panel in sidebar
```

## Map Provider

```php
'map_provider' => 'openstreetmaps',
// Options: 'openstreetmaps', 'google', 'tencent', 'yandex'
```

## Grid Actions

```php
'grid_action_class' => null,
// Set to a custom action displayer class, or null for default
```

## Extensions

```php
'extension_dir' => app_path('Admin/Extensions'),
```

## Alert Message

```php
'top_alert' => '',
// Set a string to display an alert banner at the top of every page
```

## Minification

```php
'minify_assets' => [
    'excepts' => [],  // Files to exclude from minification
],
```
