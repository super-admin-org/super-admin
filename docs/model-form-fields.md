# Form Fields

Super Admin provides 60+ form field types.

## Text Input Fields

### Text

```php
$form->text('title', 'Title');
$form->text('title')->rules('required|min:3');
$form->text('title')->icon('fa-pencil');
$form->text('title')->datalist(['option1', 'option2']);
```

### Textarea

```php
$form->textarea('body', 'Body')->rows(10);
```

### Email

```php
$form->email('email', 'Email');
```

### Password

```php
$form->password('password', 'Password');
```

### URL

```php
$form->url('homepage', 'Homepage');
```

### IP Address

```php
$form->ip('ip_address', 'IP');
```

### Phone Number

```php
$form->phonenumber('phone', 'Phone')->options(['mask' => '999 9999 9999']);
```

### Hidden

```php
$form->hidden('field_name');
$form->hidden('field_name')->value('default');
```

## Number Fields

### Number

```php
$form->number('quantity', 'Quantity');
$form->number('quantity')->min(0)->max(100);
```

### Decimal

```php
$form->decimal('price', 'Price');
```

### Currency

```php
$form->currency('price', 'Price')->symbol('$');
$form->currency('price')->symbol('EUR');
```

### Rate

```php
$form->rate('rate', 'Rate');
```

### Slider

```php
$form->slider('progress', 'Progress')
    ->attributes(['max' => 100, 'min' => 0, 'step' => 1]);
```

## Selection Fields

### Select

```php
$form->select('category', 'Category')->options([
    1 => 'Electronics',
    2 => 'Clothing',
    3 => 'Books',
]);

// From a model
$form->select('user_id', 'User')->options(User::pluck('name', 'id'));

// With AJAX loading
$form->select('user_id', 'User')->ajax('/admin/api/users');

// Cascading select (load options based on another field)
$form->select('province', 'Province')->options($provinces)
    ->load('city', '/admin/api/cities');
$form->select('city', 'City');
```

### Multiple Select

```php
$form->multipleSelect('tags', 'Tags')->options(Tag::pluck('name', 'id'));
```

### Listbox

```php
$form->listbox('permissions', 'Permissions')
    ->options(Permission::pluck('name', 'id'))
    ->height(200);
```

### Radio

```php
$form->radio('status', 'Status')->options([
    0 => 'Draft',
    1 => 'Published',
])->default(0);

// Stacked layout
$form->radio('status')->options([...])->stacked();
```

### Radio Button

```php
$form->radioButton('status', 'Status')->options([
    0 => 'Draft',
    1 => 'Published',
]);
```

### Radio Card

```php
$form->radioCard('status', 'Status')->options([
    0 => 'Draft',
    1 => 'Published',
]);
```

### Checkbox

```php
$form->checkbox('options', 'Options')->options([
    1 => 'Option A',
    2 => 'Option B',
    3 => 'Option C',
]);

// Stacked layout
$form->checkbox('options')->options([...])->stacked();
```

### Checkbox Button

```php
$form->checkboxButton('options', 'Options')->options([...]);
```

### Checkbox Card

```php
$form->checkboxCard('options', 'Options')->options([...]);
```

## Date & Time Fields

### Date

```php
$form->date('birthday', 'Birthday');
$form->date('birthday')->format('d-m-Y');
```

### Time

```php
$form->time('alarm', 'Alarm Time');
$form->time('alarm')->format('HH:mm:ss');
```

### DateTime

```php
$form->datetime('published_at', 'Published At');
$form->datetime('published_at')->format('YYYY-MM-DD HH:mm:ss');
```

### Date Range

```php
$form->dateRange('start_date', 'end_date', 'Date Range');
```

### Time Range

```php
$form->timeRange('start_time', 'end_time', 'Time Range');
```

### DateTime Range

```php
$form->datetimeRange('start', 'end', 'DateTime Range');
```

### Multiple Dates

```php
$form->dateMultiple('dates', 'Select Dates');
```

### Month / Year

```php
$form->month('birth_month', 'Month');
$form->year('birth_year', 'Year');
```

## File Fields

### Image

```php
$form->image('avatar', 'Avatar');
$form->image('avatar')->uniqueName()->removable();
```

### Multiple Images

```php
$form->multipleImage('photos', 'Photos');
$form->multipleImage('photos')->removable()->sortable();
```

### File

```php
$form->file('document', 'Document');
$form->file('document')->removable()->downloadable();
```

### Multiple Files

```php
$form->multipleFile('attachments', 'Attachments');
```

See [Image/File Upload](model-form-upload.md) for full upload configuration.

## Rich Content Fields

### Editor (Rich Text)

```php
$form->editor('body', 'Content');
```

### Tags

```php
$form->tags('keywords', 'Keywords');
```

### Icon Picker

```php
$form->icon('icon', 'Icon');
```

### Color Picker

```php
$form->color('theme_color', 'Color');
$form->color('theme_color')->default('#3c8dbc');
```

## Advanced Fields

### Switch

```php
$form->switch('active', 'Active');
```

### Map

```php
$form->map('latitude', 'longitude', 'Location')
    ->default(['lat' => 51.37, 'lng' => -0.09]);
```

### Timezone

```php
$form->timezone('timezone', 'Timezone');
```

### Key-Value

```php
$form->keyValue('settings', 'Settings');
```

### Table (Repeater)

```php
$form->table('items', 'Items', function ($table) {
    $table->text('name', 'Name');
    $table->number('quantity', 'Qty');
    $table->currency('price', 'Price');
});
```

### List

```php
$form->list('features', 'Features');
```

## Display Fields

### Display (Read-Only)

```php
$form->display('id', 'ID');
$form->display('created_at', 'Created');
```

### HTML

```php
$form->html('<div class="alert alert-info">Custom HTML</div>');
```

### Divider

```php
$form->divider();
$form->divider('Section Title');
```

## Relationship Fields

### BelongsTo

```php
$form->belongsTo('user_id', UserGrid::class, 'User');
```

### BelongsToMany

```php
$form->belongsToMany('roles', RoleGrid::class, 'Roles');
```

### HasMany

```php
$form->hasMany('comments', 'Comments', function ($form) {
    $form->text('title');
    $form->textarea('body');
});
```

### Embeds (JSON fields)

```php
$form->embeds('meta', 'Metadata', function ($form) {
    $form->text('seo_title', 'SEO Title');
    $form->textarea('seo_description', 'SEO Description');
});
```

## Common Field Methods

These methods work on all field types:

```php
->value('text')                    // Set value
->default('value')                 // Set default value
->help('Help text')                // Add help text below field
->placeholder('Enter value...')    // Placeholder text
->required()                       // Mark as required
->rules('required|min:3')         // Validation rules
->readonly()                       // Read-only
->disable()                        // Disabled
->autofocus()                      // Auto-focus on page load
->attribute(['data-id' => '1'])   // HTML attributes
->setWidth(8, 2)                   // Field and label width
```
