# Form Callbacks

Hooks let you run logic before or after form operations.

## Available Hooks

The following hooks are available on the Form class:

| Hook | When |
|------|------|
| `editing()` | Before the edit form is displayed |
| `submitted()` | When the form is submitted (before validation) |
| `saving()` | Before saving to database (create or update) |
| `saved()` | After saving to database (create or update) |
| `deleting()` | Before deleting a record |
| `deleted()` | After deleting a record |

## Saving Callbacks

### Before Save

```php
$form->saving(function (Form $form) {
    // Modify data before saving
    if ($form->password && $form->model()->password !== $form->password) {
        $form->password = bcrypt($form->password);
    }
});
```

### After Save (Saved)

```php
$form->saved(function (Form $form) {
    // Runs after create or update
    // $form->model() contains the saved model
});
```

## Conditional Logic in Saving

Use `isCreating()` and `isEditing()` to differentiate:

```php
$form->saving(function (Form $form) {
    if ($form->isCreating()) {
        $form->model()->slug = Str::slug($form->title);
    }
});
```

## Deleting Callbacks

```php
$form->deleting(function (Form $form) {
    // Clean up related files, etc.
});

$form->deleted(function (Form $form) {
    // Post-deletion logic
});
```

## Redirect After Save

```php
$form->saved(function (Form $form) {
    return $form->response()->success('Saved!')->redirect('/admin/posts');
});
```

## Prevent Save

Return a response to abort saving:

```php
$form->saving(function (Form $form) {
    if ($form->title === 'test') {
        return $form->response()->error('Invalid title');
    }
});
```

## Ignoring Fields

Prevent specific fields from being saved to the database:

```php
$form->ignore(['temp_field', 'confirmation']);
```
