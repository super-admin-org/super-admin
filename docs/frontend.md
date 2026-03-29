# CSS & JavaScript

## Adding Custom CSS

```php
// In app/Admin/bootstrap.php
Admin::css('/your/custom/css/style.css');

// Multiple files
Admin::css([
    '/css/custom1.css',
    '/css/custom2.css',
]);
```

## Adding Custom JavaScript

```php
// In app/Admin/bootstrap.php
Admin::js('/your/custom/js/script.js');

// Multiple files
Admin::js([
    '/js/custom1.js',
    '/js/custom2.js',
]);
```

## Inline Scripts

```php
Admin::script('console.log("Hello from admin!");');
```

## Inline Styles

```php
Admin::style('.custom-class { color: red; }');
```

## Per-Page Assets

Add assets in a specific controller:

```php
public function index(Content $content)
{
    Admin::css('/css/dashboard.css');
    Admin::js('/js/charts.js');

    return $content->title('Dashboard')->body(view('admin.dashboard'));
}
```

## Built-in Libraries

Super Admin includes these libraries (no need to import):

- Bootstrap 5
- Font Awesome
- Axios
- Sweetalert2
- Toastify
- Flatpickr (date/time picker)
- Choicesjs (select boxes)
- Sortablejs (drag and drop)
- Nprogress (loading bar)
- LeafletJS (maps)
- Coloris (color picker)
- Dual-Listbox

## Theming

Change the skin in `config/admin.php`:

```php
'skin' => 'skin-blue-light',
```
