# Permissions

Super Admin includes a built-in RBAC (Role-Based Access Control) system with users, roles, and permissions.

## Built-in Management Pages

Access these from the sidebar under **Auth**:

- **Users** (`/admin/auth/users`) - Manage admin accounts
- **Roles** (`/admin/auth/roles`) - Manage roles
- **Permissions** (`/admin/auth/permissions`) - Manage permissions
- **Menu** (`/admin/auth/menu`) - Manage sidebar menu

## Route Permissions

Permissions are bound to HTTP routes. When creating a permission:

1. Set a **slug** (e.g., `manage-posts`)
2. Select **HTTP methods** (GET, POST, PUT, DELETE, etc.)
3. Enter **path patterns** (e.g., `/posts*`)

A path of `/posts*` matches `/admin/posts`, `/admin/posts/create`, etc.

## Checking Permissions in Code

### Permission Check

```php
use SuperAdmin\Admin\Auth\Permission;

// Throws 403 if user doesn't have permission
Permission::check('manage-posts');
```

### Role Check

```php
// Throws 403 if user doesn't have role
Permission::allow('administrator');
```

### User Helper Methods

```php
// Get current admin user
$user = Admin::user();

// Check permission
if ($user->can('manage-posts')) {
    // Has permission
}

// Check role
if ($user->isRole('editor')) {
    // Has role
}

// Check multiple roles
if ($user->inRoles(['administrator', 'editor'])) {
    // Has one of these roles
}

// Check if administrator
if ($user->isAdministrator()) {
    // Is super admin
}
```

## Permission Middleware

Apply permissions to route groups:

### Allow Roles

```php
Route::group([
    'middleware' => 'admin.permission:allow,administrator,editor',
], function ($router) {
    $router->resource('posts', PostController::class);
});
```

### Deny Roles

```php
Route::group([
    'middleware' => 'admin.permission:deny,guest',
], function ($router) {
    // ...
});
```

### Check Permission

```php
Route::group([
    'middleware' => 'admin.permission:check,manage-posts',
], function ($router) {
    $router->resource('posts', PostController::class);
});
```

## Permission in Controller

```php
class PostController extends AdminController
{
    public function index(Content $content)
    {
        Permission::check('view-posts');
        // ...
    }

    public function create(Content $content)
    {
        Permission::check('create-posts');
        // ...
    }
}
```

## Conditional UI Elements

```php
// In grid
$grid->actions(function ($actions) {
    if (!Admin::user()->can('delete-posts')) {
        $actions->disableDelete();
    }
});

// In form
if (Admin::user()->can('manage-settings')) {
    $form->text('advanced_setting');
}
```

## Menu Permissions

When `menu_bind_permission` is enabled in `config/admin.php`, menu items are only visible to users with the associated role.

```php
// config/admin.php
'menu_bind_permission' => true,
```

## Operation Log

All admin actions are logged to the `admin_operation_log` table. Configure in `config/admin.php`:

```php
'operation_log' => [
    'enable' => true,
    'allowed_methods' => ['GET', 'HEAD', 'POST', 'PUT', 'DELETE'],
    'secret_fields' => ['password', 'password_confirmation'],
    'except' => [
        'admin/auth/logs*',  // Exclude log viewing from logging
    ],
],
```
