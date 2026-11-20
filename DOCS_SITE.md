# The documentation website

The public documentation site for this package lives in a **separate repository** — currently a sibling folder on disk at `../super-admin-docs` (not yet pushed anywhere; `git init` hasn't been run there yet). It's a Docusaurus 3 site with English (default) and Bangla translations of every page in this repo's `docs/` folder, built with real screenshots of the actual admin panel UI, lazy-loaded images, and local search.

Full build/maintenance details — architecture decisions, the translation glossary, how to add a page, a known MDX/anchor gotcha, the screenshot process — live in **`../super-admin-docs/MAINTENANCE.md`**. This file only covers what's relevant from *this* repo's side.

## When you add or change a feature

This repo's `docs/*.md` is still the source of truth for English content (per the existing rule: keep it up to date when you touch related functionality, don't create doc files unless asked). The docs site is a **separate, manually-synced copy** — editing `docs/*.md` here does not automatically update the site. After changing a doc here, go update the corresponding file in `../super-admin-docs/docs/` (same filename) and its Bangla translation in `../super-admin-docs/i18n/bn/docusaurus-plugin-content-docs/current/` — see the docs site's `MAINTENANCE.md` for the exact steps and the Bangla terminology glossary to keep translations consistent.

## Do not install `pestphp/pest-plugin-browser` in this package (except temporarily)

This package's entire test suite (`TestCase` in `tests/TestCase.php`) extends `Laravel\BrowserKitTesting\TestCase`, which defines its own `visit($uri)` method. Pest 4's browser-testing plugin (`pestphp/pest-plugin-browser`) globally mixes a `Pest\Browser\Browsable` trait — with an incompatible `visit()` signature — into every Pest test process-wide the moment it's installed, regardless of which directory or TestCase a given test uses.

The result: simply having `pestphp/pest-plugin-browser` in `composer.json` causes a **PHP fatal error** ("Declaration of `Browsable::visit()` must be compatible with `BrowserKitTesting\TestCase::visit()`") on **every single test in the suite**, not just new ones — confirmed by testing against a completely clean checkout via `git stash`.

This was discovered while building the docs site's screenshots (real Playwright-driven browser captures of the admin panel). The screenshot infrastructure (a separate `BrowserTestCase` not extending `laravel/browser-kit-testing`, a demo `Post` model/controller, and 4 screenshot tests) was built, run successfully, and then **fully removed** from this repo afterward — `git status` was verified clean before finishing. The resulting PNGs live in the docs site at `super-admin-docs/static/img/screenshots/`.

If real screenshots ever need to be recaptured (e.g. after a UI change): the docs site's `MAINTENANCE.md` has the exact temporary-install → capture → full-revert steps. Do not skip the revert step, and do not merge/commit with the plugin still in `composer.json`.

## Pre-existing test issue found (unrelated to the docs site work)

While verifying the above regression, `tests/Feature/AdminClassTest.php` was found to have 12 failing tests calling undefined methods — `Admin::title()` and `Admin::getModel()` don't appear to exist on the `SuperAdmin\Admin\Facades\Admin` facade / underlying class. Also, running the full Feature suite (`vendor/bin/pest tests/Feature`) stops entirely after this one file (non-zero exit) rather than continuing through the rest of the suite. Both reproduce identically on a completely unmodified checkout, so this predates any of this session's changes — flagging it here so it isn't lost, not something addressed as part of the docs site work.
