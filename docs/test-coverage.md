# Documentation Test Coverage

Every documented feature is backed by automated tests. This page maps docs to tests so you can verify everything works.

## How to Run Tests

```bash
# Run all tests
vendor/bin/pest

# Run only fast unit tests (class existence checks)
vendor/bin/pest tests/Unit/

# Run feature tests (need database)
vendor/bin/pest tests/Feature/
```

## Test Coverage Map

### Unit Tests (fast, no database needed)

These tests verify that every class, method, and feature mentioned in the docs actually exists in the source code. They run in ~1 second.

| Documentation Page | Test File | What's Verified |
|---|---|---|
| [Form Fields](model-form-fields.md) | `tests/Unit/ClassExistsTest.php` | All 50+ form field classes exist |
| [Grid Filters](model-grid-filters.md) | `tests/Unit/ClassExistsTest.php` | Equal, Like, Between, In, Where, Scope filters |
| [Grid Actions](model-grid-actions.md) | `tests/Unit/ClassExistsTest.php` | Actions, DropdownActions displayers |
| [Data Export](model-grid-export.md) | `tests/Unit/ClassExistsTest.php` | CsvExporter class |
| [Permissions](permissions.md) | `tests/Unit/ClassExistsTest.php` | Permission check/allow/deny methods, all Auth models |
| [Commands](commands.md) | `tests/Unit/ClassExistsTest.php` | All 9 console commands |
| [Widgets](widgets.md) | `tests/Unit/WidgetsTest.php` | Box, Tab, Table, InfoBox, Collapse, Alert, Callout, Carousel, Form |

### Feature Tests (integration, needs database)

These tests boot the full Laravel application and verify actual CRUD operations work end-to-end.

| Documentation Page | Test File | What's Verified |
|---|---|---|
| [Installation](installation.md) | `tests/Feature/InstallTest.php` | Admin directory created |
| [Quick Start](quick-start.md) | `tests/Feature/LaravelTest.php` | Laravel app boots |
| [Model Grid](model-grid.md) | `tests/Feature/UserGridTest.php` | Grid renders with columns |
| [Model Form](model-form.md) | `tests/Feature/UserFormTest.php` | Create form loads |
| [Model Form Upload](model-form-upload.md) | `tests/Feature/FileUploadTest.php` | File upload form loads |
| [Model Form Upload](model-form-upload.md) | `tests/Feature/ImageUploadTest.php` | Image upload form loads |
| [Model Tree](model-tree.md) | `tests/Feature/ModelTreeTest.php` | Tree selectOptions works |
| [Permissions](permissions.md) | `tests/Feature/PermissionsTest.php` | Permissions page loads |
| [Permissions](permissions.md) | `tests/Feature/RolesTest.php` | Roles page loads |
| [Permissions](permissions.md) | `tests/Feature/UsersTest.php` | Users page loads |
| [Configuration](configuration.md) | `tests/Feature/UserSettingTest.php` | Settings page loads |
| n/a | `tests/Feature/AuthTest.php` | Login form, authentication |
| n/a | `tests/Feature/IndexTest.php` | Dashboard loads |
| n/a | `tests/Feature/MenuTest.php` | Menu page loads |
| n/a | `tests/Feature/OperationLogTest.php` | Operation log page loads |
| n/a | `tests/BasicAuthTest.php` | Full login/logout flow |

## Adding a CI Badge

To show test status publicly, add a GitHub Actions workflow:

```yaml
# .github/workflows/tests.yml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    strategy:
      matrix:
        php: [8.2, 8.3, 8.4]
        laravel: [10.*, 11.*, 12.*, 13.*]

    steps:
      - uses: actions/checkout@v4
      - uses: shivammathur/setup-php@v2
        with:
          php-version: ${{ matrix.php }}

      - run: composer install --no-interaction
      - run: vendor/bin/pest
```

Then add this badge to your README:

```markdown
![Tests](https://github.com/super-admin-org/super-admin/actions/workflows/tests.yml/badge.svg)
```
