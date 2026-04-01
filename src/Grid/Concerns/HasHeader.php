<?php

namespace SuperAdmin\Admin\Grid\Concerns;

use Closure;
use SuperAdmin\Admin\Grid\Tools\Header;

trait HasHeader
{
    /**
     * @var Closure|string|null
     */
    protected $header;

    /**
     * Set grid header.
     *
     * @param  Closure|string|null  $content
     * @return $this|Closure|string|null
     */
    public function header(Closure|string|null $content = null)
    {
        if ($content === null) {
            return $this->header;
        }

        $this->header = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function renderHeader()
    {
        if (! $this->header) {
            return '';
        }

        return (new Header($this))->render();
    }
}
