# Model Tree

The `SuperAdmin\Admin\Tree` class displays hierarchical data (parent-child relationships) with drag-and-drop reordering.

## Setup

### 1. Migration

Your table needs `parent_id` and `order` columns:

```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->integer('parent_id')->default(0);
    $table->integer('order')->default(0);
    $table->string('title');
    $table->timestamps();
});
```

### 2. Model

Use the `ModelTree` trait:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SuperAdmin\Admin\Traits\ModelTree;

class Category extends Model
{
    use ModelTree;

    protected $table = 'categories';
}
```

### 3. Controller

```php
use App\Models\Category;
use SuperAdmin\Admin\Layout\Content;
use SuperAdmin\Admin\Tree;

class CategoryController extends AdminController
{
    public function index(Content $content)
    {
        $tree = new Tree(new Category());

        $tree->branch(function ($branch) {
            return "<strong>{$branch['title']}</strong>";
        });

        return $content
            ->title('Categories')
            ->body($tree);
    }
}
```

## Customizing Column Names

If your columns differ from the defaults:

```php
class Category extends Model
{
    use ModelTree;

    // Default: 'parent_id'
    protected $parentColumn = 'pid';

    // Default: 'order'
    protected $orderColumn = 'sort';

    // Default: 'title'
    protected $titleColumn = 'name';
}
```

## Tree Branch Display

```php
$tree->branch(function ($branch) {
    $icon = $branch['icon'] ? "<i class='fa {$branch['icon']}'></i>" : '';
    return "{$icon} {$branch['title']}";
});
```

## Query Callback

```php
$tree->query(function ($model) {
    return $model->where('type', 'category');
});
```

## Select Options

Generate a select dropdown from tree data (useful in forms):

```php
$options = Category::selectOptions();

$form->select('parent_id', 'Parent')->options($options);
```

With a custom root label:

```php
$options = Category::selectOptions(null, 'Root Category');
```
