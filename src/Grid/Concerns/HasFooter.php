<?php

namespace SuperAdmin\Admin\Grid\Concerns;

use Closure;
use SuperAdmin\Admin\Grid\Tools\Footer;

trait HasFooter
{
    /**
     * @var Closure
     */
    protected $footer;

    public $fixedFooter = true;

    /**
     * Set footer fixed.
     *
     * @param bool
     * @return $this|Closure
     */
    public function fixedFooter($bool = true)
    {
        $this->fixedFooter = $bool;

        return $this;
    }

    /**
     * Set grid footer.
     *
     *
     * @return $this|Closure
     */
    public function footer(?Closure $closure = null)
    {
        if (! $closure) {
            return $this->footer;
        }

        $this->footer = $closure;

        return $this;
    }

    /**
     * Render grid footer.
     *
     * @return string
     */
    public function renderFooter()
    {
        if (! $this->footer) {
            return '';
        }

        return (new Footer($this))->render();
    }
}
