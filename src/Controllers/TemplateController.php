<?php

namespace SuperAdmin\Admin\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use SuperAdmin\Admin\Facades\Admin;
use SuperAdmin\Admin\Layout\Content;
use SuperAdmin\Admin\Template\TemplateManager;

/**
 * Admin > Templates: list the installed templates and switch the active one.
 */
class TemplateController extends Controller
{
    public function index(Content $content): Content
    {
        $templates = Admin::template();

        return $content
            ->title(Admin::template()->text('title'))
            ->description(Admin::template()->text('description'))
            ->body(view('admin::templates', [
                'templates' => $this->cards($templates),
                'active' => $templates->active(),
                'configured' => $templates->configured(),
                'stored' => $templates->stored(),
                'switcherEnabled' => $templates->switcherEnabled(),
                'canStore' => $templates->canStore(),
            ]));
    }

    public function update(Request $request): RedirectResponse
    {
        $templates = Admin::template();

        if ($response = $this->refuseWhenLocked($templates)) {
            return $response;
        }

        $validated = $request->validate(
            ['template' => ['required', 'string', Rule::in($templates->names())]],
            ['template.in' => Admin::template()->text('unknown')]
        );

        $templates->activate($validated['template']);

        admin_toastr(Admin::template()->text('activated', [
            'template' => $templates->get($validated['template'])['label'],
        ]));

        return redirect()->route('admin.auth.templates.index');
    }

    public function destroy(): RedirectResponse
    {
        $templates = Admin::template();

        if ($response = $this->refuseWhenLocked($templates)) {
            return $response;
        }

        $templates->forget();

        admin_toastr(Admin::template()->text('reset_done', [
            'template' => $templates->get($templates->configured())['label'],
        ]));

        return redirect()->route('admin.auth.templates.index');
    }

    protected function refuseWhenLocked(TemplateManager $templates): ?RedirectResponse
    {
        if (! $templates->switcherEnabled()) {
            admin_toastr(Admin::template()->text('disabled'), 'error');
        } elseif (! $templates->canStore()) {
            admin_toastr(Admin::template()->text('not_migrated'), 'error');
        } else {
            return null;
        }

        return redirect()->route('admin.auth.templates.index');
    }

    /**
     * @return array<int, array{name: string, label: string, description: string, framework: string, swatches: array<string, string>, preview: string|null, isActive: bool, isConfigured: bool, missingViews: int}>
     */
    protected function cards(TemplateManager $templates): array
    {
        $cards = [];

        foreach ($templates->all() as $name => $template) {
            $cards[] = [
                'name' => $name,
                'label' => $template['label'],
                'description' => $template['description'],
                'framework' => $template['framework'],
                'swatches' => array_merge([
                    'sidebar' => '#1f2937',
                    'header' => '#ffffff',
                    'background' => '#f3f4f6',
                    'primary' => '#3b82f6',
                ], $template['swatches']),
                'preview' => $template['preview'] ? Admin::asset($template['preview']) : null,
                'isActive' => $name === $templates->active(),
                'isConfigured' => $name === $templates->configured(),
                'missingViews' => count($templates->missingViews($name)),
            ];
        }

        return $cards;
    }
}
