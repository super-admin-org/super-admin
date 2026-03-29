# Grid Column Usage

## Column Width

```php
$grid->column('title')->width(200);
```

## Column Color

```php
$grid->column('title')->color('#ffff00');
```

## HTML Attributes

```php
$grid->column('title')->setAttributes(['style' => 'color:red;']);
$grid->column('desc')->style('max-width:200px;word-break:break-all;');
```

## Help Text

```php
$grid->column('title')->help('This column shows the post title');
```

## Sorting

```php
// Enable sortable column
$grid->column('created_at')->sortable();

// Sortable with type casting
$grid->column('price')->sortable(false, 'DECIMAL');

// Default ordering
$grid->model()->orderBy('id', 'desc');
```

## Hide Column by Default

```php
$grid->column('created_at')->hide();
```

Users can still show it using the column selector.

## Fixed Columns

Keep first and last columns visible during horizontal scrolling:

```php
$grid->fixColumns(3, -2); // Fix first 3, last 2
```

## String Operations

Chain `Illuminate\Support\Str` methods on column values:

```php
$grid->title()->limit(30);
$grid->title()->ucfirst();
$grid->title()->substr(1, 10);
$grid->email()->limit(20, '...');
```

## Array / Collection Operations

For array or collection values, chain `Illuminate\Support\Collection` methods:

```php
$grid->tags()->pluck('name');
$grid->tags()->pluck('name')->map('ucwords')->implode(', ');
```

## Complex Transformations

```php
$grid->images()->display(function ($images) {
    return json_decode($images, true);
})->map(function ($path) {
    return 'http://localhost/images/' . $path;
})->image();
```

## Extending Columns

### Anonymous Function

```php
use SuperAdmin\Admin\Grid\Column;

Column::extend('color', function ($value, $color) {
    return "<span style='color: $color'>$value</span>";
});

// Usage
$grid->title()->color('#ccc');
```

### Class-Based Extension

```php
Column::extend('popover', Popover::class);

// Usage
$grid->description()->popover('right');
```
