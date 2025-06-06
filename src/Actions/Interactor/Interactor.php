<?php

namespace SuperAdmin\Admin\Actions\Interactor;

use SuperAdmin\Admin\Actions\Action;

abstract class Interactor
{
    /**
     * @var Action
     */
    public $action;

    /**
     * @var array
     */
    public static $elements = [
        'addValues', 'getRow', 'success', 'error', 'warning', 'info', 'question', 'confirm',
        'text', 'email', 'integer', 'ip', 'url', 'password', 'phonenumber',
        'textarea', 'map', 'select', 'multipleSelect', 'checkbox', 'radio',
        'file', 'image', 'date', 'datetime', 'time', 'hidden', 'multipleImage',
        'multipleFile', 'modalLarge', 'modalSmall',
    ];

    /**
     * Dialog constructor.
     */
    public function __construct(Action $action)
    {
        $this->action = $action;
    }

    /**
     * @return mixed
     */
    abstract public function addScript();
}
