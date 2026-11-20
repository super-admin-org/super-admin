<?php

use App\Admin\Controllers\AuthController;
use SuperAdmin\Admin\Auth\Database\Administrator;
use SuperAdmin\Admin\Auth\Database\Menu;
use SuperAdmin\Admin\Auth\Database\Permission;
use SuperAdmin\Admin\Auth\Database\Role;
use SuperAdmin\Admin\Auth\Database\Setting;
use SuperAdmin\Admin\Grid\Displayers\Actions\Actions;

return [

    /*
    |--------------------------------------------------------------------------
    | super-admin name
    |--------------------------------------------------------------------------
    |
    | This value is the name of super-admin, This setting is displayed on the
    | login page.
    |
    */
    'name' => 'Super Admin',

    /*
    |--------------------------------------------------------------------------
    | super-admin logo
    |--------------------------------------------------------------------------
    |
    | The logo of all admin pages. You can also set it as an image by using a
    | `img` tag, eg '<img src="http://logo-url" alt="Admin logo">'.
    |
    */
    'logo' => '<b>Super</b> Admin',

    /*
    |--------------------------------------------------------------------------
    | super-admin mini logo
    |--------------------------------------------------------------------------
    |
    | The logo of all admin pages when the sidebar menu is collapsed. You can
    | also set it as an image by using a `img` tag, eg
    | '<img src="http://logo-url" alt="Admin logo">'.
    |
    */
    'logo-mini' => '<b>SA</b>',

    /*
    |--------------------------------------------------------------------------
    | super-admin template
    |--------------------------------------------------------------------------
    |
    | The UI template to use for the admin panel. Available templates:
    |
    |   - 'tailwind-light'      Modern glassmorphism UI with Tailwind CSS (default)
    |   - 'bootstrap-classic'   Classic Bootstrap 5 UI (old name: 'super-admin-template-1')
    |
    | Every template lives in its own folder under the package's resources/views
    | directory. A template chosen in the admin panel (Admin > Templates) is
    | stored in the settings table and takes precedence over this value.
    |
    */
    'template' => 'tailwind-light',

    /*
    |--------------------------------------------------------------------------
    | super-admin template switcher
    |--------------------------------------------------------------------------
    |
    | Allow administrators to change the template from the admin panel
    | (Admin > Templates, route auth/templates). Set to false to always use
    | the 'template' value above.
    |
    */
    'template_switcher' => true,

    /*
    |--------------------------------------------------------------------------
    | super-admin upgrade notice
    |--------------------------------------------------------------------------
    |
    | After an update, show administrators a notice while the published assets
    | are older than the package or migrations have not run, until
    | `php artisan admin:upgrade` is run. Set to false to hide it.
    |
    */
    'upgrade_notice' => true,

    /*
    |--------------------------------------------------------------------------
    | super-admin bootstrap setting
    |--------------------------------------------------------------------------
    |
    | This value is the path of super-admin bootstrap file.
    |
    */
    'bootstrap' => app_path('Admin/bootstrap.php'),

    /*
    |--------------------------------------------------------------------------
    | super-admin route settings
    |--------------------------------------------------------------------------
    |
    | The routing configuration of the admin page, including the path prefix,
    | the controller namespace, and the default middleware. If you want to
    | access through the root path, just set the prefix to empty string.
    |
    */
    'route' => [

        'prefix' => env('ADMIN_ROUTE_PREFIX', 'admin'),

        'namespace' => 'App\\Admin\\Controllers',

        'middleware' => ['web', 'admin'],
    ],

    /*
    |--------------------------------------------------------------------------
    | super-admin install directory
    |--------------------------------------------------------------------------
    |
    | The installation directory of the controller and routing configuration
    | files of the administration page. The default is `app/Admin`, which must
    | be set before running `artisan admin::install` to take effect.
    |
    */
    'directory' => app_path('Admin'),

    /*
    |--------------------------------------------------------------------------
    | super-admin html title
    |--------------------------------------------------------------------------
    |
    | Html title for all pages.
    |
    */
    'title' => 'Admin',

    /*
    |--------------------------------------------------------------------------
    | Access via `https`
    |--------------------------------------------------------------------------
    |
    | If your page is going to be accessed via https, set it to `true`.
    |
    */
    'https' => env('ADMIN_HTTPS', false),

    /*
    |--------------------------------------------------------------------------
    | super-admin auth setting
    |--------------------------------------------------------------------------
    |
    | Authentication settings for all admin pages. Include an authentication
    | guard and a user provider setting of authentication driver.
    |
    | You can specify a controller for `login` `logout` and other auth routes.
    |
    */
    'auth' => [

        'controller' => AuthController::class,

        'guard' => 'admin',

        'guards' => [
            'admin' => [
                'driver' => 'session',
                'provider' => 'admin',
            ],
        ],

        'providers' => [
            'admin' => [
                'driver' => 'eloquent',
                'model' => Administrator::class,
            ],
        ],

        // Add "remember me" to login form
        'remember' => true,

        // Redirect to the specified URI when user is not authorized.
        'redirect_to' => 'auth/login',

        // Protect agaist brute force attacks
        'throttle_logins' => true,
        'throttle_attempts' => 5,
        'throttle_timeout' => 900, // in seconds

        // The URIs that should be excluded from authorization.
        'excepts' => [
            'auth/login',
            'auth/logout',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | super-admin two-factor authentication
    |--------------------------------------------------------------------------
    |
    | After the password, ask for a 6-digit code from an authenticator app
    | (Google Authenticator, Microsoft Authenticator, 1Password, Authy...).
    | Each user turns it on from the user menu > Two-factor.
    |
    | mode:   'off'      never ask for a code
    |         'optional' ask the users who turned it on
    |         'required' ask everybody; users without it must set it up first
    | panel:  allow administrators to change the mode from the admin panel
    |         (user menu > Two-factor). Set to false to always use 'mode'.
    | issuer: the name shown in the authenticator app (defaults to 'name').
    | window: accepted 30-second steps before and after now (clock drift).
    |
    | Run `php artisan migrate` once to add the two-factor columns.
    |
    */
    'two_factor' => [
        'mode' => env('ADMIN_TWO_FACTOR', 'optional'),
        'panel' => true,
        'issuer' => env('ADMIN_TWO_FACTOR_ISSUER'),
        'window' => 1,
        'recovery_codes' => 8,
    ],

    /*
    |--------------------------------------------------------------------------
    | super-admin upload setting
    |--------------------------------------------------------------------------
    |
    | File system configuration for form upload files and images, including
    | disk and upload path.
    |
    */
    'upload' => [

        // Disk in `config/filesystem.php`.
        'disk' => 'admin',

        // Image and file upload path under the disk above.
        'directory' => [
            'image' => 'images',
            'file' => 'files',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | super-admin database settings
    |--------------------------------------------------------------------------
    |
    | Here are database settings for super-admin builtin model & tables.
    |
    */
    'database' => [

        // Database connection for the following tables.
        'connection' => '',

        // User tables and model.
        'users_table' => 'admin_users',
        'users_model' => Administrator::class,

        // Role table and model.
        'roles_table' => 'admin_roles',
        'roles_model' => Role::class,

        // Permission table and model.
        'permissions_table' => 'admin_permissions',
        'permissions_model' => Permission::class,

        // Menu table and model.
        'menu_table' => 'admin_menu',
        'menu_model' => Menu::class,

        // Pivot table for the table above.
        'operation_log_table' => 'admin_operation_log',
        'user_permissions_table' => 'admin_user_permissions',
        'role_users_table' => 'admin_role_users',
        'role_permissions_table' => 'admin_role_permissions',
        'role_menu_table' => 'admin_role_menu',

        // Settings changed from the admin panel (e.g. the active template).
        'settings_table' => 'admin_settings',
        'settings_model' => Setting::class,

        // Run the package migrations with `php artisan migrate` without publishing them.
        // Set to false if you publish the migrations and edit your copies.
        'load_migrations' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | User operation log setting
    |--------------------------------------------------------------------------
    |
    | By setting this option to open or close the operation log in super-admin.
    |
    */
    'operation_log' => [

        'enable' => true,

        /*
         * Only logging allowed methods in the list
         */
        'allowed_methods' => ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH'],

        /*
         * Routes that will not log to a database.
         *
         * All methods to a path like: admin/auth/logs
         * or specific method to a path like: get:admin/auth/logs.
         */
        'except' => [
            env('ADMIN_ROUTE_PREFIX', 'admin').'/auth/logs*',
        ],

        /*
         * Replace input fields that should not be logged
         */
        'filter_input' => [
            'token' => '*****-filtered-out-*****',
            'password' => '*****-filtered-out-*****',
            'password_remember' => '*****-filtered-out-*****',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Indicates whether to check route permission.
    |--------------------------------------------------------------------------
    */
    'check_route_permission' => true,

    /*
    |--------------------------------------------------------------------------
    | Indicates whether to check menu roles.
    |--------------------------------------------------------------------------
    */
    'check_menu_roles' => true,

    /*
    |--------------------------------------------------------------------------
    | User default avatar
    |--------------------------------------------------------------------------
    |
    | Set a default avatar for newly created users.
    |
    */
    'default_avatar' => '/vendor/super-admin/super-admin/gfx/user.svg',

    /*
    |--------------------------------------------------------------------------
    | Admin map field provider
    |--------------------------------------------------------------------------
    |
    | Supported: "openstreetmaps", "tencent", "google", "yandex".
    |
    */
    'map_provider' => 'openstreetmaps',

    /*
    |--------------------------------------------------------------------------
    | Application Skin
    |--------------------------------------------------------------------------
    |
    | A custom class to overwrite your admin panel looks.
    | The orginal adminlte theme is not used anymore.
    |
    */
    'skin' => 'your-custom-skin-class',

    /*
    |--------------------------------------------------------------------------
    | Application layout
    |--------------------------------------------------------------------------
    |
    | This value is the layout of admin pages.
    |
    | Supported: "fixed", "layout-boxed", "layout-top-nav", "sidebar-collapse",
    | "sidebar-mini".
    |
    */
    'layout' => ['sidebar-mini', 'sidebar-collapse'],

    /*
    |--------------------------------------------------------------------------
    | Login page background image
    |--------------------------------------------------------------------------
    |
    | This value is used to set the background image of login page.
    |
    */
    'login_background_image' => '',

    /*
    |--------------------------------------------------------------------------
    | Show version at footer
    |--------------------------------------------------------------------------
    |
    | Whether to display the version number of super-admin at the footer of
    | each page
    |
    */
    'show_version' => true,

    /*
    |--------------------------------------------------------------------------
    | Show environment at footer
    |--------------------------------------------------------------------------
    |
    | Whether to display the environment at the footer of each page
    |
    */
    'show_environment' => true,

    /*
    |--------------------------------------------------------------------------
    | Menu bind to permission
    |--------------------------------------------------------------------------
    |
    | whether enable menu bind to a permission
    */
    'menu_bind_permission' => true,

    /*
    |--------------------------------------------------------------------------
    | Enable default breadcrumb
    |--------------------------------------------------------------------------
    |
    | Whether enable default breadcrumb for every page content.
    */
    'enable_default_breadcrumb' => true,

    /*
    |--------------------------------------------------------------------------
    | Enable/Disable assets minify
    |--------------------------------------------------------------------------
    */
    'minify_assets' => [

        // Assets will not be minified.
        'excepts' => [

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Enable/Disable sidebar menu search
    |--------------------------------------------------------------------------
    */
    'enable_menu_search' => true,

    /*
    |--------------------------------------------------------------------------
    | Enable/Disable user_panel in sidebar
    |--------------------------------------------------------------------------
    */
    'enable_user_panel' => false,

    /*
    |--------------------------------------------------------------------------
    | Alert message that will displayed on top of the page.
    |--------------------------------------------------------------------------
    */
    'top_alert' => '',

    /*
    |--------------------------------------------------------------------------
    | The global Grid action display class. (Actions::class, DropdownActions:class or ContextMenuActions::class)
    |--------------------------------------------------------------------------
    */
    'grid_action_class' => Actions::class,

    /*
    |--------------------------------------------------------------------------
    | Extension Directory
    |--------------------------------------------------------------------------
    |
    | When you use command `php artisan admin:extend` to generate extensions,
    | the extension files will be generated in this directory.
    */
    'extension_dir' => app_path('Admin/Extensions'),

    /*
    |--------------------------------------------------------------------------
    | Settings for extensions.
    |--------------------------------------------------------------------------
    |
    | You can find all available extensions here
    | https://github.com/super-admin-extensions.
    |
    */
    'extensions' => [

    ],
];
