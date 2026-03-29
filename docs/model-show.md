# Model Show

The `SuperAdmin\Admin\Show` class displays model details in a read-only panel.

## Basic Usage

```php
use App\Models\Post;
use SuperAdmin\Admin\Show;

protected function detail($id)
{
    $show = new Show(Post::findOrFail($id));

    $show->field('id', 'ID');
    $show->field('title', 'Title');
    $show->field('body', 'Body');
    $show->field('created_at', 'Created');
    $show->field('updated_at', 'Updated');

    return $show;
}
```

## HTML Escaping

By default, output is escaped to prevent XSS. To render HTML:

```php
$show->field('body', 'Body')->unescape();
```

## Formatting

```php
// Custom display
$show->field('avatar')->as(function ($avatar) {
    return "<img src='{$avatar}' width='100' />";
})->unescape();

// Image display
$show->field('avatar')->image();

// File download
$show->field('document')->file();

// Link
$show->field('homepage')->link();

// Badge
$show->field('status')->using([
    0 => 'Draft',
    1 => 'Published',
])->badge();
```

## Panel Customization

```php
$show->panel()
    ->style('primary')  // primary, info, danger, warning, default
    ->title('Post Details');
```

## Panel Tools

```php
$show->panel()->tools(function ($tools) {
    $tools->disableEdit();
    $tools->disableList();
    $tools->disableDelete();
});
```

## Divider

```php
$show->divider();
```

## Relationships

### One-to-One

```php
$show->profile('Profile', function ($profile) {
    $profile->field('age', 'Age');
    $profile->field('address', 'Address');
});
```

### One-to-Many

```php
$show->comments('Comments', function ($comment) {
    $comment->field('id', 'ID');
    $comment->field('body', 'Comment');
    $comment->field('created_at', 'Posted');
});
```

### Many-to-Many

```php
$show->tags('Tags', function ($tag) {
    $tag->field('name', 'Tag');
});
```

## Conditional Display

```php
$show->field('secret')->as(function ($value) {
    if (Admin::user()->isAdministrator()) {
        return $value;
    }
    return '******';
});
```
