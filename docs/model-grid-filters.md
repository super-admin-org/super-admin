# Grid Filters

Filters add a query panel above the grid for users to search and filter data.

## Basic Usage

```php
$grid->filter(function ($filter) {
    $filter->like('title', 'Title');
    $filter->equal('status', 'Status')->select([
        0 => 'Pending',
        1 => 'Active',
    ]);
});
```

## Filter Types

| Method | SQL | Example |
|--------|-----|---------|
| `equal()` | `WHERE col = ?` | `$filter->equal('status', 'Status')` |
| `notEqual()` | `WHERE col != ?` | `$filter->notEqual('status', 'Status')` |
| `like()` | `WHERE col LIKE %?%` | `$filter->like('title', 'Title')` |
| `ilike()` | `WHERE col ILIKE %?%` | `$filter->ilike('title', 'Title')` |
| `contains()` | Same as `like` | `$filter->contains('title')` |
| `startsWith()` | `WHERE col LIKE ?%` | `$filter->startsWith('title')` |
| `endsWith()` | `WHERE col LIKE %?` | `$filter->endsWith('title')` |
| `gt()` | `WHERE col > ?` | `$filter->gt('price', 'Min Price')` |
| `lt()` | `WHERE col < ?` | `$filter->lt('price', 'Max Price')` |
| `between()` | `WHERE col BETWEEN ? AND ?` | `$filter->between('price', 'Price')` |
| `in()` | `WHERE col IN (...)` | `$filter->in('status', 'Status')` |
| `notIn()` | `WHERE col NOT IN (...)` | `$filter->notIn('status', 'Status')` |
| `date()` | `WHERE DATE(col) = ?` | `$filter->date('created_at', 'Date')` |
| `day()` | `WHERE DAY(col) = ?` | `$filter->day('created_at', 'Day')` |
| `month()` | `WHERE MONTH(col) = ?` | `$filter->month('created_at', 'Month')` |
| `year()` | `WHERE YEAR(col) = ?` | `$filter->year('created_at', 'Year')` |
| `where()` | Custom query | See below |

## Custom Where Filter

```php
$filter->where(function ($query) {
    $query->where('title', 'like', "%{$this->input}%")
          ->orWhere('body', 'like', "%{$this->input}%");
}, 'Search');
```

## Presenters

Presenters control how the filter input is rendered.

### Text (default)

```php
$filter->equal('name', 'Name');
$filter->equal('name')->placeholder('Enter name...');
```

### Select

```php
$filter->equal('status')->select([
    0 => 'Draft',
    1 => 'Published',
]);

// With AJAX
$filter->equal('user_id')->select('/admin/api/users');
```

### Multiple Select

```php
$filter->in('tags')->multipleSelect([
    1 => 'PHP',
    2 => 'Laravel',
    3 => 'JavaScript',
]);
```

### Radio

```php
$filter->equal('released')->radio([
    '' => 'All',
    0  => 'No',
    1  => 'Yes',
]);
```

### Checkbox

```php
$filter->in('status')->checkbox([
    0 => 'Draft',
    1 => 'Published',
    2 => 'Archived',
]);
```

### Date/Time

```php
$filter->equal('created_at')->datetime();
$filter->equal('created_at')->date();
$filter->equal('created_at')->time();
$filter->between('created_at')->datetime();
```

## Filter Configuration

```php
// Remove the default ID filter
$filter->disableIdFilter();

// Expand filter panel by default
$filter->expand();
// or from grid:
$grid->expandFilter();

// Set column widths
$filter->setCols(4, 6);
```

## Multi-Column Layout

```php
$filter->column(1/2, function ($filter) {
    $filter->like('title', 'Title');
    $filter->equal('status', 'Status');
});

$filter->column(1/2, function ($filter) {
    $filter->equal('category', 'Category');
    $filter->between('created_at', 'Created')->datetime();
});
```

## Scope Filters

Query scope tabs appear above the grid:

```php
$filter->scope('active', 'Active')->where('status', 1);
$filter->scope('draft', 'Draft')->where('status', 0);
$filter->scope('trashed', 'Deleted')->onlyTrashed();
```

## Filter Groups

Apply multiple filter methods to one field:

```php
$filter->group('price', 'Price', function ($group) {
    $group->gt('Greater than');
    $group->lt('Less than');
    $group->equal('Equal to');
    $group->between('Between');
});
```
