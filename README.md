<p align="center">
<a href="https://super-admin.org/">
<img src="https://super-admin.org/gfx/logo.png" alt="super-admin" style="height:200px;background:transparent;">
</a>
</p>

<p align="center"><code>super-admin</code> is an administrative interface builder for Laravel which can help you build CRUD backends with just a few lines of code.</p>
<p align="center">
<a href="https://super-admin.org">Homepage</a> |
<a href="docs/index.md">Documentation</a> |
<a href="https://github.com/super-admin-org/super-admin">GitHub</a> |
<a href="https://github.com/super-admin-org?tab=repositories">Extensions</a>
</p>

<p align="center">
    <a href="https://styleci.io/repos/365864806">
        <img src="https://styleci.io/repos/365864806/shield" alt="StyleCI">
    </a>
    <a href="https://packagist.org/packages/super-admin-org/super-admin">
        <img src="https://img.shields.io/github/license/super-admin-org/super-admin.svg?style=flat-square&color=brightgreen" alt="Packagist">
    </a>
    <a href="https://packagist.org/packages/super-admin-org/super-admin">
        <img src="https://img.shields.io/packagist/dt/super-admin-org/super-admin.svg?style=flat-square" alt="Total Downloads">
    </a>
    <a href="https://github.com/super-admin-org/super-admin">
        <img src="https://img.shields.io/badge/Awesome-Laravel-brightgreen.svg?style=flat-square" alt="Awesome Laravel">
    </a>
</p>

<p align="center">
    Forked from <a href="https://github.com/z-song/laravel-admin">Laravel-admin</a> &
    <a href="https://github.com/open-admin-org/open-admin">Open-admin</a>.
    Much thanks to Z-song and the open-admin team for their effort and great setup!
</p>

<p align="center">
    Both upstream projects are no longer maintained and remain incompatible with
    Laravel 11+. This repository continues the work to support <strong>Laravel 10, 11, 12, and 13</strong>.
</p>

---

## Features

- CRUD generator for Eloquent models
- Model Grid with sorting, filtering, inline editing, export, and batch actions
- Model Form with 60+ field types, validation, tabs, and file uploads
- Model Show for detail pages with panels and relation display
- Model Tree for hierarchical/nested data
- Built-in RBAC (users, roles, permissions)
- Operation log / audit trail
- Menu management with drag-and-drop ordering
- Responsive UI built on Bootstrap 5
- Extension system for plugins
- 19 Artisan commands for scaffolding and management
- Widgets: Box, Tab, Table, InfoBox, Collapse, Form, and more

## Requirements

- PHP >= 8.2
- Laravel 10.x / 11.x / 12.x / 13.x
- Fileinfo PHP Extension

## Installation

> This package requires PHP 8.2+ and Laravel 10.0 or higher.

First, install Laravel and make sure that the database connection settings are correct.

```bash
composer require super-admin-org/super-admin
```

Publish assets and config:

```bash
php artisan vendor:publish --provider="SuperAdmin\Admin\AdminServiceProvider"
```

After running the command you can find the config file in `config/admin.php`, where you can change the install directory, database connection, or table names.

Finally, run the installer:

```bash
php artisan admin:install
```

Open `http://localhost/admin/` in your browser. Use username `admin` and password `admin` to login.

## Updating

When updating to a new version, republish the assets:

```bash
php artisan vendor:publish --tag=super-admin-assets --force
```

## Quick Start

### 1. Create a model

```bash
php artisan make:model Post -m
```

### 2. Generate a controller

```bash
php artisan admin:controller \\App\\Models\\Post
```

This creates `app/Admin/Controllers/PostController.php` with grid, form, and detail methods.

### 3. Add a route

In `app/Admin/routes.php`:

```php
$router->resource('posts', PostController::class);
```

### 4. Add a menu item

Go to `http://localhost/admin/auth/menu` and add a menu entry with URI `posts`.

That's it! You now have a full CRUD interface for your Post model.

## Documentation

Full documentation is available in the [docs/](docs/index.md) directory:

| Section | Description |
|---------|-------------|
| [Installation](docs/installation.md) | Setup and configuration |
| [Quick Start](docs/quick-start.md) | Build your first CRUD in minutes |
| [Model Grid](docs/model-grid.md) | Data tables with sorting, filters, and export |
| [Model Form](docs/model-form.md) | Forms with 60+ field types |
| [Model Form Fields](docs/model-form-fields.md) | Complete field type reference |
| [Model Form Validation](docs/model-form-validation.md) | Validation rules and messages |
| [Image/File Upload](docs/model-form-upload.md) | Upload configuration and thumbnails |
| [Model Show](docs/model-show.md) | Detail pages |
| [Model Tree](docs/model-tree.md) | Tree/nested data structures |
| [Column Display](docs/model-grid-column-display.md) | Column formatting and displayers |
| [Grid Filters](docs/model-grid-filters.md) | Filter types and presenters |
| [Grid Actions](docs/model-grid-actions.md) | Row and batch actions |
| [Permissions](docs/permissions.md) | RBAC, roles, and access control |
| [Widgets](docs/widgets.md) | Box, Tab, Table, InfoBox, and more |
| [Console Commands](docs/commands.md) | Artisan commands reference |
| [CSS/JavaScript](docs/frontend.md) | Custom assets and theming |
| [Extension Development](docs/extensions.md) | Build your own extensions |
| [Configuration](docs/configuration.md) | Config reference |

## Extensions

| Extension | Description | Version |
|-----------|-------------|---------|
| [helpers](https://github.com/super-admin-org/helpers) | Several tools to help you in development | ~1.0 |
| [media-manager](https://github.com/super-admin-org/media-manager) | Web interface to manage local files | ~1.0 |
| [config](https://github.com/super-admin-org/config) | Config manager for super-admin | ~1.0 |
| [grid-sortable](https://github.com/super-admin-org/grid-sortable) | Sortable grids | ~1.0 |
| [CkEditor](https://github.com/super-admin-org/ckeditor) | CkEditor for forms | ~1.0 |
| [api-tester](https://github.com/super-admin-org/api-tester) | Test API calls from the admin | ~1.0 |
| [scheduling](https://github.com/super-admin-org/scheduling) | Show and test your cron jobs | ~1.0 |
| [phpinfo](https://github.com/super-admin-org/phpinfo) | Show PHP info in the admin | ~1.0 |
| [log-viewer](https://github.com/super-admin-org/log-viewer) | Log viewer for Laravel | ~1.0.12 |
| [page-designer](https://github.com/super-admin-org/page-designer) | Page designer to position items freely | ~1.0.18 |
| [reporter](https://github.com/super-admin-org/reporter) | Developer-friendly exception viewer | ~1.0.18 |
| [redis-manager](https://github.com/super-admin-org/redis-manager) | Redis manager for super-admin | ~1.0.20 |

## Contributing

We are looking for active contributors:
- Testing
- Extension development
- Translating documentation
- Financing

## Built With

- [Laravel](https://laravel.com/) - [Bootstrap 5](https://getbootstrap.com/)
- [Axios](https://github.com/axios/axios) - [Font Awesome](http://fontawesome.io)
- [Choicesjs](https://github.com/Choices-js/Choices) - [Flatpickr](https://github.com/flatpickr/flatpickr)
- [Sweetalert2](https://github.com/sweetalert2/sweetalert2) - [Toastify](https://github.com/apvarun/toastify-js)
- [Sortablejs](https://github.com/SortableJS/Sortable) - [Nprogress](https://ricostacruz.com/nprogress/)
- [LeafletJS](https://leafletjs.com/) / [OpenStreetMaps](https://www.openstreetmap.org/)
- [Dual-Listbox](https://github.com/maykinmedia/dual-listbox/) - [Coloris](https://github.com/mdbassit/Coloris/)

## License

`super-admin` is licensed under [The MIT License (MIT)](LICENSE).
