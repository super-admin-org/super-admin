# Grid Actions

## Row Actions

By default, each row has Edit, Show, and Delete actions.

### Disabling Default Actions

```php
$grid->actions(function ($actions) {
    $actions->disableDelete();
    $actions->disableEdit();
    $actions->disableView();
});
```

### Adding Custom Actions

```php
use SuperAdmin\Admin\Grid\Actions\Delete;

$grid->actions(function ($actions) {
    // Prepend an action
    $actions->prepend(new MyCustomAction());

    // Append an action
    $actions->append('<a href="/custom/' . $actions->getKey() . '">Custom</a>');
});
```

### Creating a Custom Action Class

```php
<?php

namespace App\Admin\Actions;

use SuperAdmin\Admin\Actions\RowAction;

class Replicate extends RowAction
{
    public $name = 'Replicate';

    public function handle()
    {
        $model = $this->getModel();
        $newModel = $model->replicate();
        $newModel->save();

        return $this->response()->success('Replicated!')->refresh();
    }

    public function dialog()
    {
        $this->confirm('Are you sure you want to replicate this record?');
    }
}
```

Use it:

```php
$grid->actions(function ($actions) {
    $actions->add(new Replicate());
});
```

## Batch Actions

Batch actions apply to selected rows.

### Disabling Batch Actions

```php
$grid->disableBatchActions();
// or individually:
$grid->batchActions(function ($batch) {
    $batch->disableDelete();
});
```

### Custom Batch Action

```php
<?php

namespace App\Admin\Actions;

use SuperAdmin\Admin\Actions\BatchAction;

class BatchApprove extends BatchAction
{
    public $name = 'Approve Selected';

    public function handle()
    {
        foreach ($this->getModels() as $model) {
            $model->update(['status' => 1]);
        }

        return $this->response()->success('Approved!')->refresh();
    }

    public function dialog()
    {
        $this->confirm('Approve all selected records?');
    }
}
```

Use it:

```php
$grid->batchActions(function ($batch) {
    $batch->add(new BatchApprove());
});
```

## Action Display Styles

```php
// Dropdown menu (default)
$grid->setActionClass(\SuperAdmin\Admin\Grid\Displayers\Actions\DropdownActions::class);

// Context menu (right-click)
$grid->setActionClass(\SuperAdmin\Admin\Grid\Displayers\Actions\ContextMenuActions::class);

// Inline buttons
$grid->setActionClass(\SuperAdmin\Admin\Grid\Displayers\Actions\Actions::class);
```
