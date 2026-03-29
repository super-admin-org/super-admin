# Column Display

Column display methods control how data is rendered in the grid.

## Display Callback

Process values through a custom function:

```php
$grid->column('title')->display(function ($title) {
    return "<span style='color:blue'>$title</span>";
});
```

Inside the callback, `$this` refers to the current row, so you can access other fields:

```php
$grid->column('full_name')->display(function () {
    return $this->first_name . ' ' . $this->last_name;
});
```

## Value Mapping

```php
$grid->column('status')->using([
    0 => 'Pending',
    1 => 'Active',
    2 => 'Disabled',
]);
```

## Visual Elements

### Image

```php
$grid->column('avatar')->image();
$grid->column('avatar')->image('', 100, 100); // With dimensions
```

### Label

```php
$grid->column('status')->label();

// With color mapping
$grid->column('status')->label([
    'draft'     => 'default',
    'published' => 'success',
    'archived'  => 'warning',
]);
```

### Badge

```php
$grid->column('category')->badge();
$grid->column('category')->badge('info');
```

### Boolean

```php
$grid->column('released')->bool();

// Custom icons
$grid->column('released')->bool([
    1 => true,
    0 => false,
]);
```

### Progress Bar

```php
$grid->column('progress')->progressBar();
$grid->column('progress')->progressBar('primary', 'sm', 100);
```

### Link

```php
$grid->column('url')->link();
$grid->column('homepage')->link('Visit');
```

### Icon

```php
$grid->column('status')->icon([
    0 => 'fa-times text-danger',
    1 => 'fa-check text-success',
]);
```

### QR Code

```php
$grid->column('link')->qrcode();
```

### Copyable

```php
$grid->column('api_key')->copyable();
```

### Downloadable

```php
$grid->column('document')->downloadable();
```

### Secret

Mask values with asterisks (click to reveal):

```php
$grid->column('password')->secret();
```

## Date Formatting

```php
$grid->column('created_at')->dateFormat('Y-m-d');
$grid->column('created_at')->dateFormat('d M Y H:i');
```

## File Size

```php
$grid->column('size')->filesize(); // 1024 -> "1.00 KB"
```

## Dot Indicator

```php
$grid->column('status')->dot([
    1 => 'success',
    2 => 'danger',
    3 => 'info',
]);
```

## Limit Text

```php
$grid->column('description')->limit(50);
$grid->column('description')->limit(50, '...');
```

## Expand

Show hidden content on click:

```php
$grid->column('description')->expand(function () {
    return $this->description;
});
```

## Modal

Display content in a popup modal:

```php
$grid->column('details')->modal('Details', function ($model) {
    return $model->details;
});
```

## View

Render a Blade template:

```php
$grid->column('details')->view('admin.grid.details');
```

## Carousel

Display multiple images as a carousel:

```php
$grid->column('images')->carousel();
```

## Loading State

Show loading icon for specific values:

```php
$grid->column('status')->loading([1, 2, 3]);
```

## Chaining Displayers

You can chain multiple display methods:

```php
$grid->column('tags')
    ->pluck('name')
    ->map('ucwords')
    ->label('info');
```
