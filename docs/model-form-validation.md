# Form Validation

Super Admin uses Laravel's validation framework.

## Basic Rules

```php
$form->text('title')->rules('required|min:3|max:255');
$form->email('email')->rules('required|email');
$form->number('age')->rules('required|integer|min:18');
```

## Custom Error Messages

```php
$form->text('code')->rules('required|regex:/^\d+$/|min:10', [
    'regex' => 'Code must contain only numbers',
    'min'   => 'Code must be at least 10 characters',
]);
```

## Nullable Fields

For fields that can be empty, ensure the database column allows NULL:

```php
$form->text('subtitle')->rules('nullable|min:3');
```

## Creation Rules

Rules that only apply when creating a new record:

```php
$form->text('username')->creationRules('required|unique:users,username');
```

## Update Rules

Rules that only apply when editing an existing record. Use `{{id}}` as a placeholder for the current record's ID:

```php
$form->text('username')->updateRules('required|unique:users,username,{{id}}');
```

## Combined Example

```php
$form->text('email')
    ->rules('required|email')
    ->creationRules('unique:users,email')
    ->updateRules('unique:users,email,{{id}}');
```

## Unique Validation

A common pattern for the `unique` rule:

```php
// Only during creation
$form->text('slug')->creationRules('unique:posts,slug');

// During update (exclude current record)
$form->text('slug')->updateRules('unique:posts,slug,{{id}}');
```

## Using Laravel Rule Objects

```php
use Illuminate\Validation\Rule;

$form->text('email')->rules([
    'required',
    Rule::unique('users')->ignore($form->model()->id),
]);
```

## Conditional Validation

```php
$form->text('company')->rules(function ($form) {
    if ($form->model()->type === 'business') {
        return 'required|min:3';
    }
    return 'nullable';
});
```
