# Widgets

Super Admin provides reusable UI components for building dashboard pages and custom views.

## Box

```php
use SuperAdmin\Admin\Widgets\Box;

$box = new Box('Title', 'Content goes here');
$box->removable();     // Add close button
$box->collapsable();   // Add collapse button
$box->style('primary'); // primary, info, danger, warning, default

echo $box;
```

## InfoBox

Display metric cards:

```php
use SuperAdmin\Admin\Widgets\InfoBox;

$infoBox = new InfoBox('Orders', 'fa-shopping-cart', 'aqua', '/admin/orders', '150');

// Customize
$infoBox->setID('orders-box')
    ->link('/admin/orders')
    ->color('green')
    ->icon('fa-users')
    ->info('Total Users');
```

## Tab

```php
use SuperAdmin\Admin\Widgets\Tab;

$tab = new Tab();
$tab->add('Users', $userTable);
$tab->add('Orders', $orderChart);
$tab->add('Settings', $settingsForm);
```

## Table

```php
use SuperAdmin\Admin\Widgets\Table;

$headers = ['ID', 'Name', 'Email', 'Created'];
$rows = [
    [1, 'John', 'john@example.com', '2024-01-01'],
    [2, 'Jane', 'jane@example.com', '2024-01-02'],
];

$table = new Table($headers, $rows);
```

## Collapse

```php
use SuperAdmin\Admin\Widgets\Collapse;

$collapse = new Collapse();
$collapse->add('Section 1', 'Content for section 1');
$collapse->add('Section 2', 'Content for section 2');
$collapse->add('Section 3', 'Content for section 3');
```

## Alert

```php
use SuperAdmin\Admin\Widgets\Alert;

$alert = new Alert('This is a warning!', 'Warning');
$alert->style('danger'); // success, info, warning, danger
```

## Callout

```php
use SuperAdmin\Admin\Widgets\Callout;

$callout = new Callout('Important notice here', 'Notice');
$callout->style('warning');
```

## Form Widget

Standalone form (not tied to a model):

```php
use SuperAdmin\Admin\Widgets\Form;

$form = new Form();
$form->action('/admin/settings');
$form->method('POST');

$form->email('email', 'Email');
$form->password('password', 'Password');
$form->text('name', 'Name');
$form->url('website', 'Website');
$form->color('theme', 'Theme Color');
$form->date('birthday', 'Birthday');
$form->textarea('bio', 'Bio');
```

## Carousel

```php
use SuperAdmin\Admin\Widgets\Carousel;

$carousel = new Carousel([
    '/images/slide1.jpg',
    '/images/slide2.jpg',
    '/images/slide3.jpg',
]);
```

## Using Widgets in Content

```php
use SuperAdmin\Admin\Layout\Content;
use SuperAdmin\Admin\Layout\Row;
use SuperAdmin\Admin\Layout\Column;

public function index(Content $content)
{
    return $content
        ->title('Dashboard')
        ->row(function (Row $row) {
            $row->column(4, new InfoBox('Users', 'fa-users', 'aqua', '/admin/users', '100'));
            $row->column(4, new InfoBox('Orders', 'fa-cart', 'green', '/admin/orders', '50'));
            $row->column(4, new InfoBox('Revenue', 'fa-dollar', 'red', '/admin/revenue', '$5,000'));
        })
        ->row(function (Row $row) {
            $row->column(6, new Box('Recent Users', $userTable));
            $row->column(6, new Box('Recent Orders', $orderTable));
        });
}
```

## Page Layout

Build custom layouts with rows and columns:

```php
$content->row(function (Row $row) {
    // Full width
    $row->column(12, 'Full width content');
});

$content->row(function (Row $row) {
    // Two columns
    $row->column(6, 'Left');
    $row->column(6, 'Right');
});

$content->row(function (Row $row) {
    // Three columns
    $row->column(4, 'Col 1');
    $row->column(4, 'Col 2');
    $row->column(4, 'Col 3');
});
```

Column widths use Bootstrap's 12-column grid system.
