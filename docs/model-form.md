# Model Form

The `SuperAdmin\Admin\Form` class generates data forms from Eloquent models.

## Basic Usage

```php
use App\Models\Movie;
use SuperAdmin\Admin\Form;

$form = new Form(new Movie());

$form->display('id', 'ID');
$form->text('title', 'Movie Title')->rules('required');
$form->select('director', 'Director')->options($directors);
$form->textarea('description', 'Description');
$form->number('rate', 'Rate')->min(0)->max(10);
$form->switch('released', 'Released?');
$form->dateTime('release_at', 'Release Time');

return $form;
```

## Form Tools

Customize the buttons in the top-right corner:

```php
$form->tools(function ($tools) {
    $tools->disableList();      // Hide "List" button
    $tools->disableDelete();    // Hide "Delete" button
    $tools->disableView();      // Hide "View" button
});
```

## Form Footer

```php
$form->footer(function ($footer) {
    $footer->disableReset();           // Hide "Reset" button
    $footer->disableSubmit();          // Hide "Submit" button
    $footer->disableViewCheck();       // Hide "View" checkbox
    $footer->disableEditingCheck();    // Hide "Continue editing" checkbox
    $footer->disableCreatingCheck();   // Hide "Continue creating" checkbox
});
```

## Ignore Fields

Exclude fields from being saved:

```php
$form->ignore(['field1', 'field2']);
```

## Form Width

```php
$form->setWidth(10, 2); // Field width, label width
```

## Custom Action URL

```php
$form->setAction('/admin/custom-save');
```

## Detect Form State

```php
if ($form->isCreating()) {
    // Creating a new record
}

if ($form->isEditing()) {
    // Editing an existing record
}
```

## Confirmation Dialog

```php
$form->confirm('Are you sure you want to save?');
$form->confirm('Are you sure?', 'edit'); // Only on edit
$form->confirm('Are you sure?', 'create'); // Only on create
```

## Disable Submit/Reset

```php
$form->disableSubmit();
$form->disableReset();
```

See also:
- [Form Fields](model-form-fields.md) - All 60+ field types
- [Validation](model-form-validation.md) - Validation rules
- [File Upload](model-form-upload.md) - Image/file upload
- [Callbacks](model-form-callbacks.md) - Saving/updating hooks
- [Layout](model-form-layout.md) - Tabs, columns, fieldsets
