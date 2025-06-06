<?php

namespace SuperAdmin\Admin\Form\Field;

use SuperAdmin\Admin\Form\Field;

class CascadeGroup extends Field
{
    /**
     * @var array
     */
    protected $dependency;

    /**
     * @var string
     */
    protected $hide = 'hide';

    /**
     * CascadeGroup constructor.
     */
    public function __construct(array $dependency)
    {
        $this->dependency = $dependency;
    }

    /**
     * @return bool
     */
    public function dependsOn(Field $field)
    {
        return $this->dependency['column'] == $field->column();
    }

    /**
     * @return int
     */
    public function index()
    {
        return $this->dependency['index'];
    }

    /**
     * @return void
     */
    public function visiable()
    {
        $this->hide = '';
    }

    /**
     * @return string
     */
    public function render()
    {
        return <<<HTML
<div class="cascade-group {$this->dependency['class']} {$this->hide}">
HTML;
    }

    /**
     * @return void
     */
    public function end()
    {
        $this->form->html('</div>')->plain();
    }
}
