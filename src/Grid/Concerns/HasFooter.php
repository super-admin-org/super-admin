<?php

namespace SuperAdmin\Admin\Grid\Concerns;

use Closure;
use SuperAdmin\Admin\Grid\Tools\Footer;

trait HasFooter
{
    /**
     * @var Closure|string|null
     */
    protected $footer;

    public $fixedFooter = true;

    /**
     * Set footer fixed.
     *
     * @param  bool  $bool
     * @return $this
     */
    public function fixedFooter($bool = true)
    {
        $this->fixedFooter = $bool;

        return $this;
    }

    /**
     * Set grid footer.
     *
     * @param  Closure|string|null  $content
     * @return $this|Closure|string|null
     */
    public function footer(Closure|string|null $content = null)
    {
        if ($content === null) {
            return $this->footer;
        }

        $this->footer = $content;

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
