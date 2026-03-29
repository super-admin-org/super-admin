# Console Commands

Super Admin provides Artisan commands for scaffolding and management.

## Installation & Setup

```bash
# Install Super Admin
php artisan admin:install

# Uninstall Super Admin
php artisan admin:uninstall

# Publish assets
php artisan vendor:publish --provider="SuperAdmin\Admin\AdminServiceProvider"

# Force republish assets (for updates)
php artisan vendor:publish --tag=super-admin-assets --force
```

## Code Generation

### Generate Controller

```bash
# From a model
php artisan admin:controller \\App\\Models\\Post

# Custom output path
php artisan admin:controller \\App\\Models\\Post --output=app/Admin/Controllers/PostController.php
```

This generates a controller with `grid()`, `detail()`, and `form()` methods pre-configured based on your model's database columns.

### Generate Form

```bash
php artisan admin:form PostForm \\App\\Models\\Post
```

### Generate Action

```bash
php artisan admin:action ApprovePost
```

## User Management

```bash
# Create a new admin user
php artisan admin:create-user

# Reset user password
php artisan admin:reset-password
```

## Menu Management

```bash
# Generate menu items from routes
php artisan admin:generate-menu
```

## Permission Management

```bash
# Create a permission
php artisan admin:permission
```

## Database

```bash
# Export seed data
php artisan admin:export-seed

# Import extension
php artisan admin:import {extension}
```

## Assets

```bash
# Minify CSS/JS assets
php artisan admin:minify
```

## Extension Development

```bash
# Create an extension scaffold
php artisan admin:extend {name}
```
