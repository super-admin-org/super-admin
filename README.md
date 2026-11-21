<p align="center">
<a href="https://super-admin.org/">
<img src="https://super-admin.org/img/logo.svg" alt="super-admin" style="height:200px;background:transparent;">
</a>
</p>

<p align="center"><code>super-admin</code> is an administrative interface builder for Laravel which can help you build CRUD backends with just a few lines of code.</p>
<p align="center">
<a href="https://super-admin.org">Homepage</a> |
<a href="https://super-admin.org/laravel/">Documentation</a> |
<a href="https://github.com/super-admin-org/super-admin">GitHub</a> |
<a href="https://github.com/super-admin-org?tab=repositories">Extensions</a>
</p>

<p align="center">
    <a href="https://github.com/super-admin-org/super-admin/actions"><img src="https://github.com/super-admin-org/super-admin/actions/workflows/run-tests.yml/badge.svg" alt="Tests"></a>
    <a href="https://github.com/super-admin-org/super-admin/actions"><img src="https://github.com/super-admin-org/super-admin/actions/workflows/fix-php-code-style-issues.yml/badge.svg" alt="Code Style"></a>
    <a href="https://packagist.org/packages/super-admin-org/super-admin"><img src="https://img.shields.io/packagist/v/super-admin-org/super-admin.svg?style=flat-square" alt="Latest Version"></a>
    <a href="https://packagist.org/packages/super-admin-org/super-admin"><img src="https://img.shields.io/packagist/dt/super-admin-org/super-admin.svg?style=flat-square" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/super-admin-org/super-admin"><img src="https://img.shields.io/github/license/super-admin-org/super-admin.svg?style=flat-square&color=brightgreen" alt="License"></a>
</p>

<p align="center">
    Forked from <a href="https://github.com/z-song/laravel-admin">Laravel-admin</a> &
    <a href="https://github.com/open-admin-org/open-admin">Open-admin</a>.
    Much thanks to Z-song and the open-admin team for their effort and great setup!
</p>

<p align="center">
    Both upstream projects are no longer maintained and remain incompatible with
    Laravel 11+. Super Admin 2.x supports <strong>Laravel 8 through 13</strong> on PHP 8.0+.
</p>

---

## Compatibility

| Laravel | PHP | Status |
|---------|-----|--------|
| 13.x | 8.3, 8.4 | Supported |
| 12.x | 8.2, 8.3, 8.4 | Supported |
| 11.x | 8.2, 8.3, 8.4 | Supported |
| 10.x | 8.2, 8.3 | Supported |
| 9.x | 8.0, 8.1, 8.2 | Supported (end-of-life framework, see note) |
| 8.x | 8.0, 8.1 | Supported (end-of-life framework, see note) |

## Features

- **Model Grid** - Data tables with sorting, filtering, inline editing, export, and batch actions
- **Model Form** - 60+ field types, validation, tabs, file uploads, and relationship management
- **Model Show** - Detail pages with panels and relation display
- **Model Tree** - Hierarchical/nested data with drag-and-drop ordering
- **RBAC** - Built-in users, roles, and permissions management
- **Two-Factor Login** - Authenticator-app codes and recovery codes; off, optional or required for everyone
- **Operation Log** - Audit trail for all admin actions
- **Menu Management** - Sidebar menu with drag-and-drop ordering
- **Templates** - Tailwind Light (default) and Bootstrap Classic, switchable from Admin > Templates
- **Widgets** - Box, Tab, Table, InfoBox, Collapse, Alert, Carousel, and more
- **Extension System** - 12 official extensions that follow the active template
- **23 Artisan Commands** - Scaffolding, user management, code generation, and `admin:upgrade` to finish updates

## Installation

> Requires PHP 8.0+ and Laravel 8.0 or higher. Laravel 8 and 9 are end-of-life and ship with published security advisories; Composer 2.8+ refuses to install them unless `audit.block-insecure` is set to `false`. Use them only for existing applications you cannot upgrade yet.

```bash
composer require super-admin-org/super-admin
```

Publish assets and config:

```bash
php artisan vendor:publish --provider="SuperAdmin\Admin\AdminServiceProvider"
```

After running the command you can find the config file in `config/admin.php`, where you can change the install directory, database connection, or table names.

Run the installer:

```bash
php artisan admin:install
```

Open `http://localhost/admin/` in your browser. Login with username `admin` and password `admin`.

## Updating

```bash
composer update super-admin-org/super-admin
php artisan vendor:publish --tag=super-admin-assets --force
```

## Quick Start

```bash
# 1. Create a model
php artisan make:model Post -m

# 2. Generate an admin controller
php artisan admin:controller \\App\\Models\\Post

# 3. Add route in app/Admin/routes.php
#    $router->resource('posts', PostController::class);

# 4. Add menu item at /admin/auth/menu with URI "posts"
```

That's it! Full CRUD interface with grid, form, filters, and detail page.

---

## Documentation

Full documentation, in English and Bangla, is at **[super-admin.org](https://super-admin.org/laravel/)**. The same pages are in the **[`docs/`](docs/index.md)** directory of this repository.

After `composer update`, finish an update with:

```bash
php artisan admin:upgrade
```

It publishes the new assets, runs new migrations and clears compiled views. See [Upgrading](https://super-admin.org/laravel/upgrading/).

### Getting Started

| Page | Description |
|------|-------------|
| **[Installation](docs/installation.md)** | Step-by-step setup, database tables, troubleshooting |
| **[Quick Start](docs/quick-start.md)** | Build your first CRUD in 5 minutes with scaffolding |
| **[Configuration](docs/configuration.md)** | Complete config reference - auth, upload, layout, map, and more |
| **[Upgrading](docs/upgrading.md)** | Version upgrades, migration from open-admin/laravel-admin |

### Model Grid (Data Tables)

| Page | Description |
|------|-------------|
| **[Basic Usage](docs/model-grid.md)** | Create grids, add columns, query data, pagination, relationships |
| **[Column Usage](docs/model-grid-column.md)** | Width, color, sorting, help text, hide, string/collection operations |
| **[Column Display](docs/model-grid-column-display.md)** | 20+ display formatters: image, label, badge, bool, progress, link, QR code, copyable, secret, modal, expand |
| **[Filters](docs/model-grid-filters.md)** | 17 filter types (equal, like, between, gt/lt, date, where...) with select, radio, checkbox, datetime presenters |
| **[Inline Editing](docs/model-grid-inline-edit.md)** | Edit data directly in the grid: editable text, switch, select, radio, checkbox |
| **[Row & Batch Actions](docs/model-grid-actions.md)** | Custom row actions, batch operations, dropdown/context menu styles |
| **[Data Export](docs/model-grid-export.md)** | CSV/Excel export, custom exporters |
| **[Grid Tools](docs/model-grid-tools.md)** | Header, footer, total row, quick search, quick create, hot keys, column selector |

### Model Form (Data Entry)

| Page | Description |
|------|-------------|
| **[Basic Usage](docs/model-form.md)** | Create forms, tools, footer, ignore fields, detect state |
| **[Form Fields](docs/model-form-fields.md)** | **60+ field types**: text, select, radio, checkbox, date/time, file, image, editor, tags, color, map, key-value, table, switch, belongs-to, has-many, embeds, and more |
| **[Validation](docs/model-form-validation.md)** | Laravel validation rules, custom messages, creation/update rules, unique constraints |
| **[Image/File Upload](docs/model-form-upload.md)** | Storage config, thumbnails, naming, cloud storage, multiple files |
| **[Callbacks](docs/model-form-callbacks.md)** | Hooks: saving, saved, deleting, deleted, editing, submitted |
| **[Layout](docs/model-form-layout.md)** | Tabs, columns, rows, fieldsets, dividers, cascade groups, step forms |

### Model Show & Tree

| Page | Description |
|------|-------------|
| **[Model Show](docs/model-show.md)** | Detail pages with panels, formatting, relations (one-to-one, one-to-many, many-to-many) |
| **[Model Tree](docs/model-tree.md)** | Hierarchical data with ModelTree trait, drag-and-drop ordering, select options |

### Admin Features

| Page | Description |
|------|-------------|
| **[Permissions](docs/permissions.md)** | RBAC system: users, roles, permissions, route binding, middleware, operation log |
| **[Widgets](docs/widgets.md)** | Box, InfoBox, Tab, Table, Collapse, Alert, Callout, Carousel, Form widget, page layout |
| **[Console Commands](docs/commands.md)** | 23 Artisan commands: install, upgrade, generate controller/form/action, create user, reset password, two-factor, templates, export seed |
| **[CSS/JavaScript](docs/frontend.md)** | Custom assets, inline scripts, theming, built-in libraries |
| **[Extensions](docs/extensions.md)** | Build and install extensions, official plugin list |

### Verification

| Page | Description |
|------|-------------|
| **[Test Coverage Map](docs/test-coverage.md)** | Every documented feature mapped to automated tests proving it works |

---

## Extensions

All extensions work with Super Admin 1.x and 2.x. Install with `composer require super-admin-org/<name>`.

| Extension | Description | Docs |
|-----------|-------------|------|
| [helpers](https://github.com/super-admin-org/super-admin-helpers) | Several tools to help you in development | [Guide](https://super-admin.org/laravel/extension-helpers/) |
| [media-manager](https://github.com/super-admin-org/media-manager) | Web interface to manage local files | [Guide](https://super-admin.org/laravel/extension-media-manager/) |
| [config](https://github.com/super-admin-org/config) | Config manager for super-admin | [Guide](https://super-admin.org/laravel/extension-config/) |
| [grid-sortable](https://github.com/super-admin-org/grid-sortable) | Sortable grids | [Guide](https://super-admin.org/laravel/extension-grid-sortable/) |
| [CkEditor](https://github.com/super-admin-org/ckeditor) | CkEditor for forms | [Guide](https://super-admin.org/laravel/extension-ckeditor/) |
| [api-tester](https://github.com/super-admin-org/api-tester) | Test API calls from the admin | [Guide](https://super-admin.org/laravel/extension-api-tester/) |
| [scheduling](https://github.com/super-admin-org/scheduling) | Show and test your cron jobs | [Guide](https://super-admin.org/laravel/extension-scheduling/) |
| [phpinfo](https://github.com/super-admin-org/phpinfo) | Show PHP info in the admin | [Guide](https://super-admin.org/laravel/extension-phpinfo/) |
| [log-viewer](https://github.com/super-admin-org/log-viewer) | Log viewer for Laravel | [Guide](https://super-admin.org/laravel/extension-log-viewer/) |
| [page-designer](https://github.com/super-admin-org/page-designer) | Page designer to position items freely | [Guide](https://super-admin.org/laravel/extension-page-designer/) |
| [reporter](https://github.com/super-admin-org/reporter) | Developer-friendly exception viewer | [Guide](https://super-admin.org/laravel/extension-reporter/) |
| [redis-manager](https://github.com/super-admin-org/redis-manager) | Redis manager for super-admin | [Guide](https://super-admin.org/laravel/extension-redis-manager/) |

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
