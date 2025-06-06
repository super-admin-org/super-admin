<?php

namespace SuperAdmin\Admin\Grid\Column;

use SuperAdmin\Admin\Admin;
use SuperAdmin\Admin\Grid\Model;

class CheckFilter extends Filter
{
    /**
     * @var array
     */
    protected $options;

    /**
     * CheckFilter constructor.
     */
    public function __construct(array $options)
    {
        $this->options = $options;

        $this->class = [
            'all' => uniqid('column-filter-all-'),
            'item' => uniqid('column-filter-item-'),
        ];
    }

    /**
     * Add a binding to the query.
     *
     * @param  array  $value
     */
    public function addBinding($value, Model $model)
    {
        if (empty($value)) {
            return;
        }

        $model->whereIn($this->getColumnName(), $value);
    }

    /**
     * Add script to page.
     *
     * @return void
     */
    protected function addScript()
    {
        $script = <<<SCRIPT

document.querySelector('.{$this->class['all']}').addEventListener("change",function(e) {
    var setTo = (this.checked) ? true : false;
    document.querySelectorAll('.{$this->class['item']}').forEach(el=>{
        el.checked = setTo;
    })
    return false;
});


SCRIPT;

        Admin::script($script);
    }

    /**
     * Render this filter.
     *
     * @return string
     */
    public function render()
    {
        $value = $this->getFilterValue([]);

        $lists = collect($this->options)->map(function ($label, $key) use ($value) {
            $checked = in_array($key, $value) ? 'checked' : '';

            return <<<HTML
<li class="" style="margin: 0;">
    <label style="width: 100%;padding: 3px;">
        <input type="checkbox" class="{$this->class['item']}" name="{$this->getColumnName()}[]" value="{$key}" {$checked}/>&nbsp;&nbsp;&nbsp;{$label}
    </label>
</li>
HTML;
        })->implode("\r\n");

        $this->addScript();

        $allCheck = (count($value) == count($this->options)) ? 'checked' : '';
        $active = empty($value) ? '' : 'text-yellow';

        return <<<EOT
<span class="dropdown">
<form action="{$this->getFormAction()}" pjax-container method="get" style="display: inline-block;">
    <a href="javascript:void(0);" class="dropdown-toggle {$active}" data-bs-toggle="dropdown">
        <i class="icon-filter"></i>
    </a>
    <ul class="dropdown-menu" role="menu" style="padding: 10px;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);left: -70px;">

        <li>
            <ul style='padding: 0;'>
            <li class="" style="margin: 0;">
                <label style="width: 100%;padding: 3px;">
                    <input type="checkbox" class="{$this->class['all']}" {$allCheck}/>&nbsp;&nbsp;&nbsp;{$this->trans('all')}
                </label>
            </li>
                <li><hr class="dropdown-divider" /></li>
                {$lists}
            </ul>
        </li>
        <li><hr class="dropdown-divider" /></li>
        <li class="text-right">
            <button class="btn btn-sm btn-flat btn-primary pull-left" data-loading-text="{$this->trans('search')}..."><i class="icon-search"></i>&nbsp;&nbsp;{$this->trans('search')}</button>
            <span><a href="{$this->getFormAction()}" class="btn btn-sm btn-light btn-default"><i class="icon-undo"></i></a></span>
        </li>
    </ul>
</form>
</span>
EOT;
    }
}
