<?php

namespace SuperAdmin\Admin\Grid\Filter;

class Day extends Date
{
    /**
     * {@inheritdoc}
     */
    protected $query = 'whereDay';

    /**
     * @var string
     */
    protected $fieldName = 'day';
}
