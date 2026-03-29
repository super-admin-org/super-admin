# Model Grid

The `SuperAdmin\Admin\Grid` class generates data tables from Eloquent models.

## Basic Usage

```php
use App\Models\Movie;
use SuperAdmin\Admin\Grid;

$grid = new Grid(new Movie());

$grid->column('id', 'ID')->sortable();
$grid->column('title', 'Title');
$grid->column('director', 'Director');
$grid->column('rate', 'Rate');
$grid->column('released', 'Released')->bool();
$grid->column('created_at', 'Created');

return $grid;
```

## Adding Columns

```php
// Basic column
$grid->column('title', 'Title');

// Shorthand syntax
$grid->title('Title');

// Multiple columns at once
$grid->columns('email', 'username', 'phone');

// JSON field
$grid->column('profile->mobile', 'Mobile');

// Relationship field (one-to-one)
$grid->column('profile.age', 'Age');

// Relationship field (method syntax)
$grid->profile()->age();
```

## Query Modification

```php
// Filter source data
$grid->model()->where('status', 1);

// Order results
$grid->model()->orderBy('id', 'desc');

// Limit results
$grid->model()->take(100);

// Eager load relationships
$grid->model()->with(['profile', 'tags']);
```

## Pagination

```php
// Set rows per page (default: 20)
$grid->paginate(15);

// Configure page size options
$grid->perPages([10, 20, 30, 50, 100]);

// Disable pagination
$grid->disablePagination();
```

## Disabling Features

```php
$grid->disableCreateButton();    // Hide "New" button
$grid->disableActions();         // Hide row action buttons
$grid->disableRowSelector();     // Hide row checkboxes
$grid->disableFilter();          // Hide filter button
$grid->disableExport();          // Hide export button
$grid->disablePagination();      // Hide pagination
$grid->disableColumnSelector();  // Hide column selector
```

## Layout Options

```php
// Fix header when scrolling
$grid->fixHeader();

// Fix first N and last N columns when scrolling horizontally
$grid->fixColumns(3, -2);

// Enable double-click to edit
$grid->enableDblClick();
```

## Relationships

### One-to-One

```php
// Model: User belongsTo Profile
$grid->column('profile.age', 'Age');
$grid->column('profile.address', 'Address');
```

### One-to-Many

```php
$grid->column('comments', 'Comments')->display(function ($comments) {
    return count($comments);
});
```

### Many-to-Many

```php
$grid->column('tags', 'Tags')->pluck('name')->label();
```

## Collection Callback

Modify the entire collection before rendering:

```php
$grid->model()->collection(function ($collection) {
    foreach ($collection as $item) {
        $item->full_name = $item->first_name . ' ' . $item->last_name;
    }
    return $collection;
});

$grid->column('full_name', 'Full Name');
```

See also:
- [Column Usage](model-grid-column.md)
- [Column Display](model-grid-column-display.md)
- [Filters](model-grid-filters.md)
- [Actions](model-grid-actions.md)
- [Grid Tools](model-grid-tools.md)
