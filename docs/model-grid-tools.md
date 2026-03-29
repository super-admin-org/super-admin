# Grid Tools

## Header & Footer

### Custom Header

```php
$grid->header(function ($query) {
    $total = $query->sum('price');
    return "<div class='text-center'>Total: \${$total}</div>";
});
```

### Custom Footer

```php
$grid->footer(function ($query) {
    return "<div>Total records: {$query->count()}</div>";
});
```

### Total Row

Display totals at the bottom of columns:

```php
$grid->column('price')->totalRow();
$grid->column('quantity')->totalRow();

// With custom formatting
$grid->column('price')->totalRow(function ($total) {
    return '$' . number_format($total, 2);
});
```

## Quick Search

Add a search input to the grid header:

```php
$grid->quickSearch('title');

// Search multiple columns
$grid->quickSearch('title', 'body', 'author.name');

// Custom search
$grid->quickSearch(function ($model, $query) {
    $model->where('title', 'like', "%{$query}%");
});
```

## Quick Create

Add a quick create form above the grid:

```php
$grid->quickCreate(function ($form) {
    $form->text('title', 'Title');
    $form->select('category', 'Category')->options([...]);
});
```

## Selector

Add predefined filter tabs:

```php
$grid->selector(function ($selector) {
    $selector->select('category', 'Category', [
        1 => 'Electronics',
        2 => 'Clothing',
        3 => 'Books',
    ]);

    $selector->selectOne('brand', 'Brand', [
        1 => 'Apple',
        2 => 'Samsung',
    ]);
});
```

## Hot Keys

Enable keyboard shortcuts:

```php
$grid->enableHotKeys();
```

Default shortcuts:
- `c` - Create new record
- `/` - Focus search input

## Custom Tools

Add custom buttons to the grid toolbar:

```php
$grid->tools(function ($tools) {
    $tools->append('<a class="btn btn-sm btn-primary" href="/admin/import">Import</a>');
});
```

## Column Selector

Let users show/hide columns:

```php
// Enabled by default. To disable:
$grid->disableColumnSelector();
```

## Soft Deleting

If your model uses `SoftDeletes`, enable the trash filter:

```php
// The grid automatically detects SoftDeletes and adds a trash filter scope
$grid->model()->withTrashed();
```
