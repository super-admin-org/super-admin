<?php

namespace SuperAdmin\Admin\Grid\Tools;

use Illuminate\Pagination\LengthAwarePaginator;
use SuperAdmin\Admin\Grid;

class Paginator extends AbstractTool
{
    /**
     * @var \Illuminate\Pagination\LengthAwarePaginator
     */
    protected $paginator = null;

    /**
     * @var bool
     */
    protected $perPageSelector = true;

    /**
     * Create a new Paginator instance.
     */
    public function __construct(Grid $grid, $perPageSelector = true)
    {
        $this->grid = $grid;
        $this->perPageSelector = $perPageSelector;

        $this->initPaginator();
    }

    /**
     * Initialize work for Paginator.
     *
     * @return void
     */
    protected function initPaginator()
    {
        $this->paginator = $this->grid->model()->eloquent();

        if ($this->paginator instanceof LengthAwarePaginator) {
            $this->paginator->appends(request()->all());
        }
    }

    /**
     * Get Pagination links.
     *
     * @return string
     */
    protected function paginationLinks()
    {
        return $this->paginator->render('admin::pagination');
    }

    /**
     * Get per-page selector.
     *
     * @return PerPageSelector
     */
    protected function perPageSelector()
    {
        if (! $this->perPageSelector) {
            return;
        }

        return new PerPageSelector($this->grid);
    }

    /**
     * Get range infomation of paginator.
     *
     * @return string|\Symfony\Component\Translation\TranslatorInterface
     */
    protected function paginationRanger()
    {
        $parameters = [
            'first' => $this->paginator->firstItem(),
            'last' => $this->paginator->lastItem(),
            'total' => $this->paginator->total(),
        ];

        $parameters = collect($parameters)->flatMap(function ($parameter, $key) {
            return [$key => "<b>$parameter</b>"];
        });

        return trans('admin.pagination.range', $parameters->all());
    }

    /**
     * Render Paginator.
     *
     * @return string
     */
    public function render()
    {
        if (! $this->grid->showPagination()) {
            return '';
        }

        $vars = [
            'range' => $this->paginationRanger(),
            'links' => $this->paginationLinks(),
            'per_page' => $this->perPageSelector(),
            'fixedFooter' => $this->grid->fixedFooter,
        ];

        return view('admin::grid.pagination', $vars)->render();
    }
}
