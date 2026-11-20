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
| [Installation](installation.md) | `tests/Feature/ConsoleCommandsTest.php` | Commands, tables, seed data |
| [Quick Start](quick-start.md) | `tests/Feature/LaravelTest.php` | Laravel app boots |
| [Commands](commands.md) | `tests/Feature/ConsoleCommandsTest.php` | All console commands registered |
| [Configuration](configuration.md) | `tests/Feature/ConfigurationTest.php` | All config options |
| [Configuration](configuration.md) | `tests/Feature/UserSettingTest.php` | Settings page loads |
| [Model Grid](model-grid.md) | `tests/Feature/UserGridTest.php` | Grid renders with columns |
| [Model Grid](model-grid.md) | `tests/Feature/GridDisplayTest.php` | Grid construction, options |
| [Grid Column](model-grid-column.md) | `tests/Feature/GridColumnTest.php` | Column displayers, actions |
| [Grid Column](model-grid-column.md) | `tests/Feature/GridColumnConfigTest.php` | Column width, color, attributes |
| [Column Display](model-grid-column-display.md) | `tests/Feature/GridColumnDisplayMethodsTest.php` | All display methods |
| [Grid Actions](model-grid-actions.md) | `tests/Feature/GridActionsTest.php` | Row/batch actions, displayers |
| [Grid Filters](model-grid-filters.md) | `tests/Feature/GridFilterTest.php` | Filter types, conditions |
| [Inline Editing](model-grid-inline-edit.md) | `tests/Feature/GridInlineEditTest.php` | Editable, switch, select |
| [Grid Export](model-grid-export.md) | `tests/Feature/GridExportTest.php` | CsvExporter, export options |
| [Grid Tools](model-grid-tools.md) | `tests/Feature/GridToolsTest.php` | Header, footer, quickSearch, selector |
| [Model Form](model-form.md) | `tests/Feature/UserFormTest.php` | Create form loads |
| [Form Fields](model-form-fields.md) | `tests/Feature/FormFieldsTest.php` | Basic field types |
| [Form Fields](model-form-fields.md) | `tests/Feature/FormFieldTypesTest.php` | All 60+ field types |
| [Form Validation](model-form-validation.md) | `tests/Feature/FormValidationTest.php` | Rules, creationRules, updateRules |
| [Form Callbacks](model-form-callbacks.md) | `tests/Feature/FormCallbacksTest.php` | Saving, saved, deleting hooks |
| [Form Layout](model-form-layout.md) | `tests/Feature/FormLayoutTest.php` | Tabs, columns, rows, fieldset |
| [Form Upload](model-form-upload.md) | `tests/Feature/FormUploadFieldsTest.php` | Image, file, multipleImage |
| [Form Upload](model-form-upload.md) | `tests/Feature/FileUploadTest.php` | File upload form loads |
| [Form Upload](model-form-upload.md) | `tests/Feature/ImageUploadTest.php` | Image upload form loads |
| [Model Show](model-show.md) | `tests/Feature/ShowBuilderTest.php` | Show construction, fields |
| [Model Show](model-show.md) | `tests/Feature/ShowFieldsTest.php` | Field display methods, panel |
| [Model Tree](model-tree.md) | `tests/Feature/ModelTreeTest.php` | Tree selectOptions works |
| [Model Tree](model-tree.md) | `tests/Feature/TreeBuilderTest.php` | Tree construction, tools |
| [Permissions](permissions.md) | `tests/Feature/PermissionsTest.php` | Permissions page loads |
| [Permissions](permissions.md) | `tests/Feature/RolesTest.php` | Roles page loads |
| [Permissions](permissions.md) | `tests/Feature/UsersTest.php` | Users page loads |
| [RBAC](rbac-architecture.md) | `tests/Feature/RbacTest.php` | Full RBAC system tests |
| [Extensions](extensions.md) | `tests/Feature/ExtensionsTest.php` | Extension registration |
| [Frontend](frontend.md) | `tests/Feature/FrontendAssetsTest.php` | CSS, JS, script, style |
| [Templates](templates.md) | `tests/Feature/FrontendAssetsTest.php` | Template config |
| n/a | `tests/Feature/AdminClassTest.php` | Admin facade methods |
| n/a | `tests/Feature/AdminCrudTest.php` | User/Role/Permission CRUD |
| n/a | `tests/Feature/AuthSettingsTest.php` | Auth settings page |
| n/a | `tests/Feature/AuthTest.php` | Login form, authentication |
| n/a | `tests/Feature/ContentLayoutTest.php` | Content layout builder |
| n/a | `tests/Feature/IndexTest.php` | Dashboard loads |
| n/a | `tests/Feature/MenuTest.php` | Menu page loads |
| n/a | `tests/Feature/MiddlewareTest.php` | Middleware classes |
| n/a | `tests/Feature/OperationLogTest.php` | Operation log page loads |
| n/a | `tests/Feature/ServiceProviderTest.php` | Service provider, routes |
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
