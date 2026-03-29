<?php

namespace SuperAdmin\Admin\Grid\Concerns;

use Closure;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use SuperAdmin\Admin\Grid\Tools\TotalRow;

trait HasTotalRow
{
    /**
     * @var array
     */
    protected $totalRowColumns = [];

    /**
     * @param  string  $column
     * @param  Closure  $callback
     * @return $this
     */
    public function addTotalRow($column, $callback)
    {
        $this->totalRowColumns[$column] = $callback;

        return $this;
    }

    /**
     * @return Factory|View|string
     */
    public function renderTotalRow($columns = null)
    {
        if (empty($this->totalRowColumns)) {
            return '';
        }

        $query = $this->model()->getQueryBuilder();

        $totalRow = new TotalRow($query, $this->totalRowColumns);

        $totalRow->setGrid($this);

        if ($columns) {
            $totalRow->setVisibleColumns($columns);
        }

        return $totalRow->render();
    }
}
