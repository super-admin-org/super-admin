<?php

namespace SuperAdmin\Admin\Grid\Tools;

use Illuminate\Support\Arr;
use SuperAdmin\Admin\Grid\Concerns\HasQuickSearch;

class QuickSearch extends AbstractTool
{
    /**
     * @var string
     */
    protected $view = 'admin::grid.quick-search';

    /**
     * @var string
     */
    protected $placeholder;

    /**
     * Set placeholder.
     *
     * @param  string  $text
     * @return $this
     */
    public function placeholder($text = '')
    {
        $this->placeholder = $text;

        return $this;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $query = request()->query();

        Arr::forget($query, HasQuickSearch::$searchKey);

        $vars = [
            'action' => request()->url().'?'.http_build_query($query),
            'key' => HasQuickSearch::$searchKey,
            'value' => request(HasQuickSearch::$searchKey),
            'placeholder' => $this->placeholder,
        ];

        return view($this->view, $vars);
    }
}
