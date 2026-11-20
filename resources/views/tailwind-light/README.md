# Tailwind Light Template

Modern glassmorphism UI template built with Tailwind CSS.

**Status:** Stable (default template since 1.3.0). Provides every view of the base template (`bootstrap-classic`); `php artisan admin:template` shows "complete".

## Design

- Frosted glass effects with backdrop blur
- Gradient backgrounds
- Rounded corners and soft shadows
- Translucent panels
- Modern typography with Inter font
- Tailwind CSS utility classes

## Files

- `template.json` - name, description, framework, preview swatches and the base CSS/JS loaded on every page.
- `*.blade.php` - the views; same paths as every other template.
- `extensions/<view namespace>/` (optional) - restyled views of an extension, e.g. `extensions/super-admin-logs/logs.blade.php`.

## Extensions and custom views

Extensions write Bootstrap 5 class names from the shared vocabulary (`SuperAdmin\Admin\Template\Vocabulary`). The "Extension compatibility layer" at the end of `resources/assets/tailwind-light/css/app.css` maps every one of them to this design, so extension pages match without changes. Rebuild after editing it: `npm run build`.

## Fallback

A view missing here is taken from `bootstrap-classic`, the base template. This template ships all of them, so nothing falls back.
