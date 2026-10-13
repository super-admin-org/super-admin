<?php

namespace SuperAdmin\Admin\Show;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use SuperAdmin\Admin\Show;

class Panel implements Renderable
{
    /**
     * The view to be rendered.
     *
     * @var string
     */
    protected $view = 'admin::show.panel';

    /**
     * The fields that this panel holds.
     *
     * @var Collection
     */
    protected $fields;

    /**
     * Variables in the view.
     *
     * @var array
     */
    protected $data;

    /**
     * Parent show instance.
     *
     * @var Show
     */
    protected $parent;

    /**
     * Panel constructor.
     */
    public function __construct(Show $show)
    {
        $this->parent = $show;

        $this->initData();
    }

    /**
     * Initialize view data.
     */
    protected function initData()
    {
        $this->data = [
            'fields' => new Collection,
            'tools' => new Tools($this),
            'style' => 'none',
            'title' => trans('admin.detail'),
            'footer' => '',
        ];
    }

    /**
     * Set the footer content rendered below the fields.
     *
     * @param  \Closure|Renderable|string  $footer
     * @return $this
     */
    public function footer($footer)
    {
        $this->data['footer'] = $footer;

        return $this;
    }

    /**
     * Resolve the footer to a string.
     */
    protected function renderFooter(): string
    {
        $footer = $this->data['footer'];

        if ($footer instanceof \Closure) {
            $footer = $footer($this);
        }

        if ($footer instanceof Renderable) {
            $footer = $footer->render();
        }

        return (string) $footer;
    }

    /**
     * Set parent container.
     *
     *
     * @return $this
     */
    public function setParent(Show $show)
    {
        $this->parent = $show;

        return $this;
    }

    /**
     * Get parent container.
     *
     * @return Show
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set style for this panel.
     *
     * @param  string  $style
     * @return $this
     */
    public function style($style = 'info')
    {
        $this->data['style'] = $style;

        return $this;
    }

    /**
     * Set title for this panel.
     *
     * @param  string  $title
     * @return $this
     */
    public function title($title)
    {
        $this->data['title'] = $title;

        return $this;
    }

    /**
     * Set view for this panel to render.
     *
     * @param  string  $view
     * @return $this
     */
    public function view($view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Build panel tools.
     */
    public function tools($callable)
    {
        call_user_func($callable, $this->data['tools']);
    }

    /**
     * Fill fields to panel.
     *
     * @param []Field $fields
     * @return $this
     */
    public function fill($fields)
    {
        $this->data['fields'] = $fields;

        return $this;
    }

    /**
     * Render this panel.
     *
     * @return string
     */
    public function render()
    {
        return view($this->view, array_merge($this->data, ['footer' => $this->renderFooter()]))->render();
    }
}
