# Changelog

Documentation: https://super-admin.org/laravel/

## v2.0.1 - 2026-11-20

### Fixed

- v2.0.0 still reported version 1.3.0 (`Admin::VERSION`, the footer and the published assets' `version.txt`); it now says 2.0.1.

### Changed

- Package information: homepage https://super-admin.org, documentation https://super-admin.org/laravel/ (`support.docs`), issues and source links, an up-to-date description and keywords.
- The 12 extensions have new releases that accept super-admin 2.x (`^1.0|^2.0`); the ones released before v2.0.0 required `^1.0` and could not be installed with it.

## v2.0.0 - 2026-11-20

### Added

- **Two-factor authentication** (TOTP, RFC 6238) for admin users, with no extra package: after the password, users type a 6-digit code from an authenticator app (Google Authenticator, Microsoft Authenticator, 1Password, Authy...) or a one-time recovery code.
  - User menu > **Two-factor** (`/admin/auth/two-factor`): scan a QR code (an SVG drawn on the server, so the secret never goes to a third-party service), confirm with a code, save 8 recovery codes, create new ones, or turn it off with the current password.
  - `admin.two_factor.mode` (`ADMIN_TWO_FACTOR` in `.env`): `off`, `optional` (default, each user decides) or `required` (users without it are sent to the setup page). Administrators can change the mode on the same page (stored in `admin_settings`); `'panel' => false` locks it to the config value.
  - Admin > Users shows a **2FA** column and a **Reset two-factor** row action for users who lost their phone.
  - `php artisan admin:two-factor [--mode=off|optional|required] [--reset] [--disable=<username>]`.
  - Secrets are encrypted with `APP_KEY`, recovery codes are stored hashed, a code works once, and wrong codes are throttled with `admin.auth.throttle_attempts` / `throttle_timeout`.
  - Upgrade: `php artisan vendor:publish --tag=super-admin-migrations`, then `php artisan migrate` (adds three nullable columns to `admin_users`). Until then the feature stays off.
- **Updates finish themselves**:
  - Assets are also tagged `laravel-assets`. Laravel apps already run `vendor:publish --tag=laravel-assets --force` after every `composer update`, so new CSS/JS arrive without a manual step.
  - Migrations load from the package (`loadMigrationsFrom`), so `php artisan migrate` runs new ones without publishing. Copies published by older versions keep working and nothing runs twice. `admin.database.load_migrations => false` turns this off.
  - `php artisan admin:upgrade` publishes the assets, runs the new super-admin and extension migrations (asks first in production unless `--force`), and clears compiled views. Add `"@php artisan admin:upgrade --no-interaction"` to `post-update-cmd` in the app's composer.json to run it after every update.
  - Administrators see a notice in the panel while the published assets are older than the package or migrations have not run (`admin.upgrade_notice`).
  - Extensions can declare `public $migrations = __DIR__.'/../database/migrations';`; `Extension::boot()` loads them and `admin:upgrade` includes them.
- **Admin > Templates** page (`/admin/auth/templates`): lists the installed templates with a preview and switches the active one; the choice is stored in the new `admin_settings` table and overrides `config/admin.php`. `'template_switcher' => false` turns it off.
- `php artisan admin:template [name] [--reset] [--menu]`: list or switch templates from the CLI; `--menu` adds the Templates menu item and the `/auth/templates` permission path to existing installs.
- `php artisan admin:lint-views <paths>`: reports classes in extension views that only some templates style.
- `Admin::template()` (`SuperAdmin\Admin\Template\TemplateManager`): template registry with `template.json` manifests, `register()` for templates shipped by other packages, and `extensionViews()` so extension views follow the active template (`<template>/extensions/<namespace>/` and `<views>/<template>/` overrides).
- Shared class vocabulary (`SuperAdmin\Admin\Template\Vocabulary`) and `--sa-*` CSS variables that every template styles; tailwind-light gained a compatibility layer for it, so extensions written with Bootstrap 5 markup match the template.

### Changed

- The `create_admin_tables` migration skips tables that already exist, so it is safe when an app renamed its published copy.
- `resources/views/` now only contains template folders. The classic views moved to `bootstrap-classic/` (formerly `super-admin-template-1`, which still works as an alias); the duplicate root copies were removed. Both templates provide all 145 views.
- Base CSS/JS lists come from each template's `template.json` instead of a hard-coded `tailwind-light` check.
- Importing an extension twice no longer duplicates its menu items or fails on an existing permission slug.

### Fixed

- Tailwind template: the remove (×) button on selected tags of multiple selects (e.g. Roles, Permissions) was a white cross on a light tag and could not be seen; it is blue now and red on hover.
- Tailwind template: extension pages (log viewer, helpers, media manager, ...) lost their styling because Tailwind purged the Bootstrap compatibility classes they use; Bootstrap `.collapse` panels rendered expanded.
- Tailwind template: form rows had an extra gap under every field; wide content (e.g. the scaffold field table) widened the whole page.

## v1.3.0 - 2026-10-12 (never tagged; released as part of v2.0.0)

Template release: `tailwind-light` (default) and `super-admin-template-1` (classic Bootstrap 5).

### Fixed

- Grid `->editable()` crashed with "class Editable does not have a method text"; inline edit now resolves the displayer by type and rejects unknown types.
- Toastr and alert partials failed with "Call to a member function get() on array" when sessions are JSON serialized (Laravel 13 default); flashes are normalized with the new `admin_flash_to_array()` helper.
- Upload field preview prefixed the storage URL to values that were already absolute URLs (for example the default avatar).
- Tailwind template: form and grid footers were positioned with `position: fixed` inside a backdrop-filter card and overlapped the last rows; they are now sticky.
- Tailwind template: inline edit, grid pickers, action modals, form tabs and hasMany tabs called Bootstrap JS that is not loaded; a small `bootstrap` compatibility layer (Modal, Popover, Tooltip, Tab, Collapse, Dropdown) is bundled with the template.
- Tailwind template: UI listeners were bound again after every PJAX load, the sidebar active state was not refreshed (a `pjax:complete` event is now dispatched), the sidebar menu search was not wired, batch action buttons never appeared, the action form modal was visible on page load and between/datetime filters had no label.
- `Admin::VERSION` now matches the released version.
- Guests were redirected in a loop when `auth.excepts` did not include the login page; the configured `redirect_to` path now always passes the auth middleware.
- Permission denials and the grid's "page out of range" redirect sent the response and called `exit`, skipping the remaining middleware and terminating the process; they now raise an `HttpResponseException` (`Pjax::respond()` does the same).
- `Form::isCreating()` / `isEditing()` crashed outside a routed request; form fields rendered outside a web request failed with "Undefined variable $errors".
- `Grid::fixColumns()` and `fixHeader()` return the grid for chaining.
- Built-in batch actions (batch delete, batch edit) registered the generic action handler next to their own script, so every batch delete logged *Action method BatchDelete::handle() does not exist* and left the list stale; actions without a `handle()` method no longer register the generic handler.
- The sidebar menu now re-renders after a menu item is created, edited, moved or deleted on the Menu page (it used to need a full page reload).

### Added

- Laravel 8 and 9 support (PHP 8.0+): constraints widened, `symfony/dom-crawler` 5.4 accepted, CI matrix extended, operation log `user_id` cast to integer (SQLite on PHP 8.0 returned strings). Laravel 4 to 7 remain unsupported: they need PHP 5.4 to 7.x and lack the framework APIs the package is built on.
- Cascade helpers `whenIn()`, `whenNot()` and `whenNotIn()` on form fields.
- `dateMultiple()` form field alias (the type was registered as `DateMultiple` only).
- `Grid::disableDefaults()` to switch off actions, batch actions, create button, export, filter, row selector, pagination, tools and the column selector at once.
- `Show\Panel::footer()` rendered below the detail fields in both templates.
- The Admin service is a container singleton, also available as `app('admin')`; `Admin::navbar()` / `getNavbar()` are static.
- Test suite: `tests/controllers` added to the Composer classmap, test config derived from the shipped config, and the optional Excel exporter skipped when `maatwebsite/excel` is not installed.

## First release of super-admin - 2025-08-11

First release of super-admin

**Full Changelog**: https://github.com/super-admin-org/super-admin/compare/v1.1.3...v1.1.5
