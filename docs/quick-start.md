# Quick Start

This guide walks you through creating a full CRUD interface for a database table.

## Using Scaffolding (Recommended)

The helpers extension provides a scaffolding tool:

```bash
composer require super-admin-ext/helpers
php artisan admin:import helpers
```

Then go to `http://localhost/admin/helpers/scaffold` to generate controllers with a visual interface.

## Manual Setup

### Step 1: Create a Model and Migration

```bash
php artisan make:model Post -m
```

Edit the migration:

```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('body');
    $table->integer('rate')->default(0);
    $table->boolean('released')->default(false);
    $table->timestamps();
});
```

Run the migration:

```bash
php artisan migrate
```

### Step 2: Generate a Controller

```bash
php artisan admin:controller \\App\\Models\\Post
```

This creates `app/Admin/Controllers/PostController.php`:

```php
<?php

namespace App\Admin\Controllers;

use App\Models\Post;
use SuperAdmin\Admin\Controllers\AdminController;
use SuperAdmin\Admin\Form;
use SuperAdmin\Admin\Grid;
use SuperAdmin\Admin\Show;

class PostController extends AdminController
{
    protected $title = 'Posts';

    protected function grid()
    {
        $grid = new Grid(new Post());

        $grid->column('id', __('ID'))->sortable();
        $grid->column('title', __('Title'));
        $grid->column('rate', __('Rate'));
        $grid->column('released', __('Released'))->bool();
        $grid->column('created_at', __('Created'));

        $grid->filter(function ($filter) {
            $filter->like('title', 'Title');
            $filter->equal('released', 'Released')->radio([
                '' => 'All',
                1  => 'Yes',
                0  => 'No',
            ]);
        });

        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(Post::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('title', __('Title'));
        $show->field('body', __('Body'));
        $show->field('rate', __('Rate'));
        $show->field('released', __('Released'));
        $show->field('created_at', __('Created'));

        return $show;
    }

    protected function form()
    {
        $form = new Form(new Post());

        $form->text('title', __('Title'))->rules('required|min:3');
        $form->textarea('body', __('Body'))->rows(10);
        $form->number('rate', __('Rate'))->min(0)->max(10);
        $form->switch('released', __('Released'));

        return $form;
    }
}
```

### Step 3: Add Routes

In `app/Admin/routes.php`:

```php
$router->resource('posts', PostController::class);
```

### Step 4: Add a Menu Item

Navigate to `http://localhost/admin/auth/menu` and create a menu entry:

- **Title:** Posts
- **Icon:** fa-list
- **URI:** posts

### Done!

Visit `http://localhost/admin/posts` to see your CRUD interface with:

- Sortable data table with pagination
- Filter panel
- Create / Edit / Delete actions
- Detail view
