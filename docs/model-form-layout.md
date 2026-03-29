# Form Layout

## Tabs

Organize form fields into tabs:

```php
$form->tab('Basic Info', function ($form) {
    $form->text('title', 'Title');
    $form->textarea('body', 'Body');
})->tab('SEO', function ($form) {
    $form->text('meta_title', 'Meta Title');
    $form->textarea('meta_description', 'Meta Description');
})->tab('Media', function ($form) {
    $form->image('cover', 'Cover Image');
    $form->multipleImage('gallery', 'Gallery');
});
```

## Column Layout

Split the form into columns:

```php
$form->column(6, function ($form) {
    $form->text('first_name', 'First Name');
    $form->text('last_name', 'Last Name');
});

$form->column(6, function ($form) {
    $form->email('email', 'Email');
    $form->phonenumber('phone', 'Phone');
});
```

## Row Layout

```php
$form->row(function ($row) {
    $row->width(6)->text('first_name', 'First Name');
    $row->width(6)->text('last_name', 'Last Name');
});

$form->row(function ($row) {
    $row->width(4)->text('city', 'City');
    $row->width(4)->text('state', 'State');
    $row->width(4)->text('zip', 'ZIP');
});
```

## Fieldset

Group related fields:

```php
$form->fieldset('Address', function ($form) {
    $form->text('street', 'Street');
    $form->text('city', 'City');
    $form->text('state', 'State');
    $form->text('zip', 'ZIP Code');
});
```

## Divider

Visual separator between fields:

```php
$form->text('title');
$form->divider();
$form->textarea('body');

// With title
$form->divider('Additional Options');
```

## Cascade Groups

Show/hide fields based on another field's value:

```php
$form->radio('type', 'Type')->options([
    'url'  => 'URL',
    'text' => 'Text',
])->when('url', function ($form) {
    $form->url('link', 'URL');
})->when('text', function ($form) {
    $form->textarea('content', 'Content');
});
```

## Step Form (Widget)

For multi-step wizards, use the `MultipleSteps` or `StepForm` widget directly in your content:

```php
use SuperAdmin\Admin\Widgets\StepForm;

public function create(Content $content)
{
    $form = new StepForm();

    $form->add('Basic Info', function ($step) {
        $step->text('title', 'Title');
    });

    $form->add('Details', function ($step) {
        $step->textarea('body', 'Body');
    });

    return $content->title('Create Post')->body($form);
}
```
