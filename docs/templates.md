# Templates

Super Admin ships two UI templates. Every page, including extension pages, follows the active template.

## Available templates

| Template | CSS framework | Style | Status |
|----------|---------------|-------|--------|
| `tailwind-light` | Tailwind CSS | Glassmorphism, frosted-glass cards, dark sidebar | Default |
| `bootstrap-classic` | Bootstrap 5 | Classic admin panel, white cards | Base template |

`bootstrap-classic` was called `super-admin-template-1` before; the old name still works as an alias.

## Switching templates

### From the admin panel

Open **Admin > Templates** (`/admin/auth/templates`). Each installed template is shown as a card with a preview, its framework and whether it provides every view. Click **Activate** on a card; the page reloads in the new template.

The choice is saved in the `admin_settings` table and overrides `config/admin.php`. **Use config default** removes the saved choice.

Access works like any other admin page: administrators can always open it; other roles need a permission whose HTTP path includes `/auth/templates` (new installs add it to the "Auth management" permission).

To hide the switcher, set `'template_switcher' => false` in `config/admin.php`; the saved choice is then ignored.

### From config

```php
// config/admin.php
'template' => 'tailwind-light',   // or 'bootstrap-classic'
```

### From the command line

```bash
php artisan admin:template                     # list templates, show the active one
php artisan admin:template bootstrap-classic   # activate (saved like the admin panel does)
php artisan admin:template --reset             # forget the saved choice, use config again
```

### Upgrading an existing install

The switcher needs the settings table and the menu item:

```bash
php artisan vendor:publish --tag=super-admin-migrations
php artisan migrate
php artisan admin:template --menu      # adds Admin > Templates and the /auth/templates permission path
php artisan vendor:publish --tag=super-admin-assets --force
```

Language files published before this version do not contain the `admin.templates.*` lines; the page falls back to English. Copy the `templates` block from the package's `resources/lang/en/admin.php` to translate it.

## How templates work

The package's `resources/views/` directory contains one folder per template and nothing else:

```
resources/views/
├── bootstrap-classic/
│   ├── template.json        (label, description, framework, swatches, base CSS/JS)
│   ├── index.blade.php
│   ├── login.blade.php
│   ├── partials/ grid/ form/ show/ tree/ widgets/ ...
│   └── templates.blade.php  (the Admin > Templates page)
└── tailwind-light/
    ├── template.json
    └── ... (the same view paths)
```

Both templates provide the same 145 views. `php artisan admin:template` reports "complete" for a template that has them all.

### View lookup order (`admin::` views)

1. `resources/views/vendor/admin/<template>/` in your app (override for one template)
2. `resources/views/vendor/admin/` in your app (override for every template)
3. the active template, then the templates it `extends` (see `template.json`)
4. `bootstrap-classic`, the base template, so a view a template lacks still renders

### Base assets

`template.json` lists the CSS and JS loaded on every page (`css`, `js`). `Admin::baseCss($list)` / `Admin::baseJs($list)` in `app/Admin/bootstrap.php` still replace them.

A bundle built with `php artisan admin:minify` holds the assets of the template that was active at the time, and is only served while that template is active. Run `admin:minify` again after switching templates, or `php artisan admin:minify --clear` to remove it.

## Extensions and templates

Extensions do not need a copy of their views per template. They follow three rules:

1. **Markup uses the shared class vocabulary**: the Bootstrap 5 class names listed in `SuperAdmin\Admin\Template\Vocabulary` (`card`, `card-header`, `table table-hover`, `badge bg-danger`, `nav nav-pills`, `list-group`, `accordion`, `d-flex`, `mt-3`, ...), plus classes prefixed with the extension's own name. Every template must style the vocabulary: Bootstrap templates get it from Bootstrap, `tailwind-light` maps it to its design in the "Extension compatibility layer" of `resources/assets/tailwind-light/css/app.css`.
2. **Colours come from the `--sa-*` CSS variables** (`--sa-primary`, `--sa-danger`, `--sa-border`, `--sa-surface-muted`, ...), which every template defines.
3. **Behaviour uses `data-bs-toggle` / `data-bs-target`** (collapse, tab, modal, dropdown) and the `window.bootstrap` API subset (Modal, Collapse, Tab, Dropdown, Tooltip, Popover); no jQuery.

Check an extension with:

```bash
php artisan admin:lint-views vendor/super-admin-org/log-viewer/resources --ignore=log-
```

It lists classes that only some templates style (Bootstrap 3/AdminLTE names such as `box-body`, `label-success`, `no-padding`, or Tailwind utilities). `--ignore` skips the extension's own classes (a trailing `-` means a prefix).

### Registering extension views

Register views through the template manager instead of `loadViewsFrom()`:

```php
public function boot()
{
    \SuperAdmin\Admin\Facades\Admin::template()->extensionViews('my-extension', __DIR__.'/../resources/views');
}
```

An extension class with a `$views` property is registered automatically under its `$name`. Lookup order for `my-extension::page`:

1. `resources/views/vendor/my-extension/<template>/page.blade.php` (app, one template)
2. `resources/views/vendor/my-extension/page.blade.php` (app, every template)
3. `<template folder>/extensions/my-extension/page.blade.php` (a template restyling the extension)
4. `<extension views>/<template>/page.blade.php` (the extension shipping a per-template variant)
5. `<extension views>/page.blade.php`

## Creating a template

1. Copy `resources/views/tailwind-light/` (or `bootstrap-classic/`) to a new folder, e.g. `resources/views/my-template/`.
2. Edit `template.json`: `label`, `description`, `framework`, `swatches` (preview colours), `css` and `js` (base assets), and optionally `"extends": "tailwind-light"` to inherit views you do not change.
3. Restyle the views. Keep the vocabulary and the `--sa-*` variables styled, so extensions fit.
4. A template in another package registers itself from its service provider:

   ```php
   \SuperAdmin\Admin\Facades\Admin::template()->register('my-template', __DIR__.'/../resources/views/my-template');
   ```

It then appears in **Admin > Templates**. The Pest test `TemplateSwitcherTest` checks that every template provides every view and styles the whole vocabulary.

## Building Tailwind CSS (tailwind-light)

```bash
npm install
npm run build      # writes resources/assets/tailwind-light/dist
```

The built files are committed, so end users do not need Node.js. Republish them in an app with `php artisan vendor:publish --tag=super-admin-assets --force`.

Tailwind only keeps classes it finds in `resources/views/tailwind-light/**` and `src/**`; extension views are not scanned, which is why extensions use the vocabulary (listed in `src/Template/Vocabulary.php`, so it is always kept) and not Tailwind utilities.

## JavaScript in the tailwind-light template

The template does not load Bootstrap's JavaScript. Its bundle (`resources/assets/tailwind-light/dist/super-admin-js.js`) provides:

- Dropdown, collapse, tab and modal behaviour for `data-sa-toggle` / `data-sa-target` and the Bootstrap spelling (`data-bs-toggle`, `data-bs-target`, `data-bs-dismiss`, `data-bs-parent` for accordions). Bootstrap's `.collapse` / `.show` and the `collapsed` trigger class work as in Bootstrap.
- A `window.bootstrap` compatibility layer with `Modal`, `Popover`, `Tooltip`, `Tab`, `Collapse` and `Dropdown` classes (`getInstance`, `getOrCreateInstance`, `show`, `hide`, `toggle`, `dispose`, and the `show.bs.*` / `shown.bs.*` / `hide.bs.*` / `hidden.bs.*` events).
- Re-initialisation after PJAX page loads: the shared `admin.ajax` script dispatches a `pjax:complete` event on `document` after every page load.

Form and grid footers use `position: sticky` (not `fixed`), because `.glass-card` uses `backdrop-filter`, which makes it the containing block for fixed descendants.
