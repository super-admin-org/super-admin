<?php

namespace SuperAdmin\Admin\Form\Field;

class Fieldset
{
    protected $name = '';

    public function __construct()
    {
        $this->name = uniqid('fieldset-');
    }

    public function start($title)
    {
        return <<<HTML
<div class="form-fieldset">
    <div class="form-fieldset-legend">
        <button type="button" data-sa-toggle="collapse" data-sa-target="#{$this->name}" class="{$this->name}-title">
        <span class="collapse-icon transition-transform rotate-90">&#9656;</span>&nbsp;&nbsp;{$title}
        </button>
    </div>
    <div id="{$this->name}">
HTML;
    }

    public function end()
    {
        return '</div></div>';
    }
}
