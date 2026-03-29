# Inline Editing

Edit data directly in the grid without opening a form.

## Editable Fields

```php
$grid->column('title')->editable();
```

## Editable Select

```php
$grid->column('status')->editable('select', [
    0 => 'Draft',
    1 => 'Published',
]);
```

## Switch

```php
$grid->column('released')->switch();
```

## Switch Group

Toggle multiple boolean fields:

```php
$grid->column('switch_group')->switchGroup([
    'hot'       => 'Hot',
    'new'       => 'New',
    'recommend' => 'Recommended',
]);
```

## Inline Select

```php
$grid->column('status')->select([
    0 => 'Pending',
    1 => 'Active',
    2 => 'Disabled',
]);
```

## Inline Radio

```php
$grid->column('gender')->radio([
    'm' => 'Male',
    'f' => 'Female',
]);
```

## Inline Checkbox

```php
$grid->column('options')->checkbox([
    1 => 'Option A',
    2 => 'Option B',
    3 => 'Option C',
]);
```

## Inline Input

```php
$grid->column('price')->input('number');
$grid->column('note')->textarea();
```
