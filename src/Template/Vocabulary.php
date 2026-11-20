<?php

namespace SuperAdmin\Admin\Template;

/**
 * The class names shared by every template.
 *
 * Extensions (and custom views) may use these Bootstrap 5 class names plus
 * their own prefixed classes; every template must style them. Bootstrap based
 * templates get them from Bootstrap itself, other templates map them to their
 * own design (see the "Extension compatibility layer" in
 * resources/assets/tailwind-light/css/app.css).
 *
 * This file is also part of the Tailwind content scan, which keeps every class
 * listed here in the compiled tailwind-light stylesheet.
 */
final class Vocabulary
{
    /**
     * @var array<string, array<int, string>>
     */
    public const GROUPS = [
        'layout' => [
            'container', 'container-fluid', 'row', 'col', 'col-auto', 'clearfix',
            'g-0', 'g-1', 'g-2', 'g-3', 'g-4', 'g-5', 'gap-1', 'gap-2', 'gap-3',
            'd-none', 'd-block', 'd-inline', 'd-inline-block', 'd-flex', 'd-inline-flex', 'd-grid',
            'flex-row', 'flex-column', 'flex-wrap', 'flex-nowrap', 'flex-fill', 'flex-grow-1', 'flex-shrink-0',
            'justify-content-start', 'justify-content-end', 'justify-content-center', 'justify-content-between', 'justify-content-around',
            'align-items-start', 'align-items-end', 'align-items-center', 'align-items-baseline', 'align-items-stretch',
            'align-self-start', 'align-self-end', 'align-self-center',
            'float-start', 'float-end', 'float-none', 'float-left', 'float-right', 'pull-left', 'pull-right',
            'w-25', 'w-50', 'w-75', 'w-100', 'w-auto', 'h-100', 'mw-100',
            'position-relative', 'position-absolute', 'position-sticky', 'sticky-top',
            'overflow-auto', 'overflow-hidden', 'visually-hidden', 'hide', 'show', 'fade', 'collapse', 'collapsing', 'collapsed', 'active', 'disabled',
        ],
        'spacing' => [
            'm-0', 'm-1', 'm-2', 'm-3', 'm-auto', 'mt-0', 'mt-1', 'mt-2', 'mt-3', 'mt-4', 'mt-5',
            'mb-0', 'mb-1', 'mb-2', 'mb-3', 'mb-4', 'mb-5', 'ms-0', 'ms-1', 'ms-2', 'ms-3', 'ms-auto',
            'me-0', 'me-1', 'me-2', 'me-3', 'me-auto', 'mx-auto', 'my-0', 'my-1', 'my-2', 'my-3',
            'p-0', 'p-1', 'p-2', 'p-3', 'p-4', 'pt-0', 'pt-1', 'pt-2', 'pt-3', 'pt-4', 'pb-0', 'pb-1', 'pb-2', 'pb-3', 'pb-4',
            'ps-0', 'ps-2', 'ps-3', 'ps-4', 'pe-0', 'pe-2', 'pe-3', 'px-0', 'px-2', 'px-3', 'py-0', 'py-1', 'py-2', 'py-3',
        ],
        'text' => [
            'small', 'lead', 'fw-normal', 'fw-semibold', 'fw-bold', 'font-monospace', 'text-nowrap', 'text-break', 'text-truncate',
            'text-start', 'text-center', 'text-end', 'text-uppercase', 'text-decoration-none',
            'text-primary', 'text-secondary', 'text-success', 'text-danger', 'text-warning', 'text-info',
            'text-muted', 'text-dark', 'text-light', 'text-white', 'text-body',
        ],
        'colors' => [
            'bg-primary', 'bg-secondary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'bg-light', 'bg-dark', 'bg-white', 'bg-transparent',
            'border', 'border-0', 'border-top', 'border-bottom', 'border-start', 'border-end',
            'border-primary', 'border-secondary', 'border-success', 'border-danger', 'border-warning', 'border-info',
            'rounded', 'rounded-0', 'rounded-circle', 'rounded-pill', 'shadow', 'shadow-sm', 'shadow-none',
        ],
        'buttons' => [
            'btn', 'btn-primary', 'btn-secondary', 'btn-success', 'btn-danger', 'btn-warning', 'btn-info', 'btn-light', 'btn-dark', 'btn-link',
            'btn-outline-primary', 'btn-outline-secondary', 'btn-outline-success', 'btn-outline-danger', 'btn-outline-warning', 'btn-outline-info',
            'btn-xs', 'btn-sm', 'btn-lg', 'btn-group', 'btn-group-sm', 'btn-group-vertical', 'btn-toolbar', 'btn-close',
        ],
        'forms' => [
            'form-group', 'form-control', 'form-control-sm', 'form-select', 'form-select-sm', 'form-label', 'col-form-label', 'form-text',
            'form-check', 'form-check-inline', 'form-check-input', 'form-check-label', 'form-switch',
            'input-group', 'input-group-sm', 'input-group-lg', 'input-group-text',
            'is-invalid', 'is-valid', 'invalid-feedback', 'valid-feedback',
        ],
        'components' => [
            'card', 'card-header', 'card-body', 'card-footer', 'card-title', 'card-subtitle', 'card-text', 'card-tools',
            'table', 'table-sm', 'table-hover', 'table-striped', 'table-bordered', 'table-borderless', 'table-responsive', 'table-light', 'table-active',
            'badge', 'alert', 'alert-primary', 'alert-secondary', 'alert-success', 'alert-danger', 'alert-warning', 'alert-info', 'alert-light', 'alert-dark', 'alert-dismissible',
            'callout', 'callout-info', 'callout-warning', 'callout-danger', 'callout-success',
            'nav', 'nav-item', 'nav-link', 'nav-tabs', 'nav-pills', 'nav-fill', 'tab-content', 'tab-pane',
            'list-group', 'list-group-item', 'list-group-item-action', 'list-group-flush', 'list-group-horizontal',
            'accordion', 'accordion-item', 'accordion-header', 'accordion-button', 'accordion-collapse', 'accordion-body', 'accordion-flush',
            'breadcrumb', 'breadcrumb-item',
            'dropdown', 'dropup', 'dropdown-toggle', 'dropdown-menu', 'dropdown-menu-end', 'dropdown-item', 'dropdown-divider', 'dropdown-header',
            'modal', 'modal-dialog', 'modal-dialog-scrollable', 'modal-dialog-centered', 'modal-lg', 'modal-xl', 'modal-sm', 'modal-content', 'modal-header', 'modal-title', 'modal-body', 'modal-footer',
            'progress', 'progress-bar', 'spinner-border', 'spinner-border-sm', 'pagination', 'page-item', 'page-link',
        ],
    ];

    /**
     * Class name families allowed by pattern instead of by name.
     *
     * @var array<int, string>
     */
    public const PATTERNS = [
        '/^col-(sm-|md-|lg-|xl-)?([1-9]|1[0-2])$/',
        '/^offset-(sm-|md-|lg-|xl-)?([1-9]|1[0-1])$/',
        '/^(icon|fa|fas|far|fab)(-[a-z0-9-]+)?$/',
    ];

    /**
     * Bootstrap 3/4, AdminLTE and Tailwind names. A class like these that is not in
     * the vocabulary is reported by `admin:lint-views`, because only some
     * templates style it.
     *
     * @var array<int, string>
     */
    public const FRAMEWORK_NAMES = [
        'btn', 'card', 'box', 'table', 'nav', 'navbar', 'badge', 'label', 'alert', 'callout', 'modal', 'dropdown',
        'panel', 'well', 'caret', 'divider', 'progress', 'pagination', 'breadcrumb', 'accordion', 'border', 'rounded',
        'shadow', 'hidden', 'invisible', 'visible', 'help-block', 'control-label', 'no-padding', 'no-margin',
        'with-border', 'form-horizontal', 'form-inline', 'input-lg', 'input-sm', 'img-responsive', 'img-circle',
        'flex', 'grid', 'block', 'inline-block', 'inline-flex', 'truncate', 'uppercase', 'italic', 'underline',
    ];

    /**
     * @var array<int, string>
     */
    public const FRAMEWORK_PATTERNS = [
        '/^(btn|card|box|table|nav|navbar|list-group|badge|label|bg|text|flex|justify-content|align-items|align-self|col|offset|input-group|form|custom|accordion|callout|alert|breadcrumb|modal|dropdown|dropup|tab|progress|pull|float|border|rounded|shadow|position|fw|fs|font|info-box|small-box|panel|well|pagination|spinner|img|sticky|overflow|gap|space|ring|divide|min|max|inset|top|bottom|left|right|z|opacity|leading|tracking|glass|items|justify|self|whitespace|backdrop|transition|duration|hover|focus|sm|md|lg|xl)-/',
        '/^[mp][tbsexylr]?-(\d+|auto|px)$/',
        '/^[dwh]-/',
    ];

    /**
     * @return array<int, string>
     */
    public static function classes(): array
    {
        return array_merge(...array_values(self::GROUPS));
    }

    public static function allows(string $class): bool
    {
        if (in_array($class, self::classes(), true)) {
            return true;
        }

        foreach (self::PATTERNS as $pattern) {
            if (preg_match($pattern, $class)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Whether a class looks like a framework class (as opposed to an extension's own class).
     */
    public static function looksLikeFramework(string $class): bool
    {
        if (in_array($class, self::FRAMEWORK_NAMES, true)) {
            return true;
        }

        foreach (self::FRAMEWORK_PATTERNS as $pattern) {
            if (preg_match($pattern, $class)) {
                return true;
            }
        }

        return false;
    }
}
