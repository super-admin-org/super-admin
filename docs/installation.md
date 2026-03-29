# Installation

## Requirements

- PHP >= 8.2
- Laravel 10.x / 11.x / 12.x / 13.x
- Fileinfo PHP Extension

## Install

First, install Laravel and make sure that the database connection settings are correct.

```bash
composer require super-admin-org/super-admin
```

Then publish assets and config:

```bash
php artisan vendor:publish --provider="SuperAdmin\Admin\AdminServiceProvider"
```

This generates the configuration file at `config/admin.php`. You can change the install directory, database connection, or table names before proceeding.

Finally, run the installer:

```bash
php artisan admin:install
```

Open `http://localhost/admin/` in your browser and log in with:

- **Username:** `admin`
- **Password:** `admin`

## Generated Files

After installation, the following files and directories are created:

### Configuration

`config/admin.php` - All settings for the admin panel.

### Admin Directory

Your development work happens in `app/Admin/`:

```
app/Admin/
├── Controllers/
│   ├── HomeController.php      # Dashboard
│   ├── AuthController.php      # Login/logout
│   └── ExampleController.php   # Sample CRUD controller
├── routes.php                  # Admin routes
└── bootstrap.php               # Admin bootstrapper
```

### Static Assets

Frontend assets are published to `/public/packages/admin/`.

### Database Tables

The installer creates these tables (configurable in `config/admin.php`):

| Table | Purpose |
|-------|---------|
| `admin_users` | Admin user accounts |
| `admin_roles` | Roles |
| `admin_permissions` | Permissions |
| `admin_menu` | Sidebar menu items |
| `admin_role_users` | User-role pivot |
| `admin_role_permissions` | Role-permission pivot |
| `admin_role_menu` | Role-menu pivot |
| `admin_user_permissions` | User-permission pivot |
| `admin_operation_log` | Audit log |

## Troubleshooting

If you encounter issues during installation:

1. Ensure your database connection is configured in `.env`
2. Run `php artisan migrate` if tables are missing
3. Clear caches: `php artisan config:clear && php artisan cache:clear`
4. Republish assets: `php artisan vendor:publish --tag=super-admin-assets --force`
