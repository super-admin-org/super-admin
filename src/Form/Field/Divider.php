<?php

namespace SuperAdmin\Admin\Form\Field;

use SuperAdmin\Admin\Form\Field;

class Divider extends Field
{
    protected $title;

    public function __construct($title = '')
    {
        $this->title = $title;
    }

    public function render()
    {
        if (empty($this->title)) {
            return '<hr class="form-divider">';
        }

        return <<<HTML
<div class="form-divider-titled">
  <span class="form-divider-title">{$this->title}</span>
</div>
HTML;
    }
}
