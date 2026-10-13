<?php

namespace SuperAdmin\Admin\Grid\Displayers;

use Illuminate\Support\Arr;

class Editable extends AbstractDisplayer
{
    /**
     * @var array
     */
    protected $arguments = [];

    /**
     * Type of editable.
     *
     * @var string
     */
    protected $type = '';

    /**
     * Options of editable function.
     *
     * @var array
     */
    protected $options = [
        'emptytext' => '<i class="icon-pencil"></i>',
    ];

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Add options for editable.
     *
     * @param  array  $options
     */
    public function addOptions($options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * Add attributes for editable.
     *
     * @param  array  $attributes
     */
    public function addAttributes($attributes = [])
    {
        $this->attributes = array_merge($this->attributes, $attributes);
    }

    /**
     * Resolve the inline-edit displayer type from the editable() arguments.
     *
     * @return array the remaining arguments to pass to the displayer
     */
    protected function buildEditableOptions(array $arguments = []): array
    {
        $this->type = Arr::get($arguments, 0, 'text');

        return array_slice($arguments, 1);
    }

    /**
     * Resolve the displayer class for the editable type.
     */
    protected function getDisplayerClass(): string
    {
        $class = __NAMESPACE__.'\\'.ucfirst($this->type);

        if (! class_exists($class) || ! is_subclass_of($class, AbstractDisplayer::class)) {
            throw new \InvalidArgumentException("Inline editable type [{$this->type}] is not supported.");
        }

        return $class;
    }

    /**
     * @return string
     */
    public function display()
    {
        $this->options['name'] = $this->getName();

        $arguments = $this->buildEditableOptions(func_get_args());

        $class = $this->getDisplayerClass();
        $displayer = new $class($this->value, $this->grid, $this->column, $this->row);
        $displayer->options = $this->options;

        return $displayer->display(...$arguments);
    }
}
