<?php

namespace SuperAdmin\Admin\Form\Field;

use SuperAdmin\Admin\Form;

class Captcha extends Text
{
    protected $rules = 'required|captcha';

    protected $view = 'admin::form.captcha';

    public function __construct($column, $arguments = [])
    {
        if (! class_exists(\Mews\Captcha\Captcha::class)) {
            throw new \Exception('To use captcha field, please install [mews/captcha] first.');
        }

        $this->column = '__captcha__';
        $this->label = trans('admin.captcha');
    }

    public function setForm(?Form $form = null)
    {
        $this->form = $form;

        $this->form->ignore($this->column);

        return $this;
    }

    public function render()
    {
        $this->script = <<<JS
document.querySelector('#{$this->column}-captcha').addEventlistener("click",function () {
    this.setAttribute('src', this.getAttribute('src')+'?'+Math.random());
});
JS;

        return parent::render();
    }
}
