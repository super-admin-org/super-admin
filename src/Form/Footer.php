<?php

namespace SuperAdmin\Admin\Form;

use Illuminate\Contracts\Support\Renderable;

class Footer implements Renderable
{
    /**
     * Footer view.
     *
     * @var string
     */
    protected $view = 'admin::form.footer';

    /**
     * Form builder instance.
     *
     * @var Builder
     */
    protected $builder;

    /**
     * Available buttons.
     *
     * @var array
     */
    protected $buttons = ['reset', 'submit'];

    /**
     * Available checkboxes.
     *
     * @var array
     */
    protected $checkboxes = ['view', 'continue_editing', 'continue_creating'];

    /**
     * @var string
     */
    protected $defaultCheck;

    /**
     * @var string
     */
    public $fixedFooter = true;

    /**
     * Footer constructor.
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Disable reset button.
     *
     * @return $this
     */
    public function disableReset(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->buttons, 'reset');
        } elseif (! in_array('reset', $this->buttons)) {
            array_push($this->buttons, 'reset');
        }

        return $this;
    }

    /**
     * Disable submit button.
     *
     * @return $this
     */
    public function disableSubmit(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->buttons, 'submit');
        } elseif (! in_array('submit', $this->buttons)) {
            array_push($this->buttons, 'submit');
        }

        return $this;
    }

    /**
     * Disable View Checkbox.
     *
     * @return $this
     */
    public function disableViewCheck(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->checkboxes, 'view');
        } elseif (! in_array('view', $this->checkboxes)) {
            array_push($this->checkboxes, 'view');
        }

        return $this;
    }

    /**
     * Disable Editing Checkbox.
     *
     * @return $this
     */
    public function disableEditingCheck(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->checkboxes, 'continue_editing');
        } elseif (! in_array('continue_editing', $this->checkboxes)) {
            array_push($this->checkboxes, 'continue_editing');
        }

        return $this;
    }

    /**
     * Disable Creating Checkbox.
     *
     * @return $this
     */
    public function disableCreatingCheck(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->checkboxes, 'continue_creating');
        } elseif (! in_array('continue_creating', $this->checkboxes)) {
            array_push($this->checkboxes, 'continue_creating');
        }

        return $this;
    }

    /**
     * Set `view` as default check.
     *
     * @return $this
     */
    public function checkView()
    {
        $this->defaultCheck = 'view';

        return $this;
    }

    /**
     * Set `continue_creating` as default check.
     *
     * @return $this
     */
    public function checkCreating()
    {
        $this->defaultCheck = 'continue_creating';

        return $this;
    }

    /**
     * Set `continue_editing` as default check.
     *
     * @return $this
     */
    public function checkEditing()
    {
        $this->defaultCheck = 'continue_editing';

        return $this;
    }

    /**
     * Set `continue_editing` as default check.
     *
     * @return $this
     */
    public function fixedFooter($set = true)
    {
        $this->fixedFooter = $set;

        return $this;
    }

    /**
     * Render footer.
     *
     * @return string
     */
    public function render()
    {
        $submitRedirects = [
            'continue_editing' => 'continue_editing',
            'continue_creating' => 'continue_creating',
            'view' => 'view',
            // 'exit' => 'exit', // can be exit as well when doing ajax request
        ];

        $data = [
            'width' => $this->builder->getWidth(),
            'buttons' => $this->buttons,
            'checkboxes' => $this->checkboxes,
            'submit_redirects' => $submitRedirects,
            'default_check' => $this->defaultCheck,
            'fixedFooter' => $this->fixedFooter,
        ];

        return view($this->view, $data)->render();
    }
}
