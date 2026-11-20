<?php

namespace SuperAdmin\Admin\Template;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use SuperAdmin\Admin\Auth\Database\Setting;
use Throwable;

/**
 * Discovers, resolves and activates admin templates.
 *
 * A template is a directory of Blade views plus an optional `template.json`
 * manifest. The package ships its templates under `resources/views/<name>/`,
 * other packages can add theirs with {@see register()}.
 *
 * View lookup order for the `admin::` namespace:
 *   1. resources/views/vendor/admin/<template>/  (app override for one template)
 *   2. resources/views/vendor/admin/             (app override for every template)
 *   3. the template and the templates it extends
 *   4. the base template (bootstrap-classic), so a view a template lacks still renders
 *
 * View lookup order for an extension namespace (see {@see extensionViews()}):
 *   1. resources/views/vendor/<namespace>/<template>/
 *   2. resources/views/vendor/<namespace>/
 *   3. <template path>/extensions/<namespace>/  (a template restyling an extension)
 *   4. <extension views>/<template>/            (an extension shipping a per-template view)
 *   5. <extension views>/                       (the extension's generic views)
 */
class TemplateManager
{
    /**
     * Key of the active template in the settings table.
     */
    public const SETTING_KEY = 'template';

    /**
     * Template every other template falls back to.
     */
    public const BASE_TEMPLATE = 'bootstrap-classic';

    /**
     * @var array<string, array{name: string, path: string, label: string, description: string, framework: string, aliases: array<int, string>, extends: string|null, swatches: array<string, string>, css: array<int, string>, js: array<int, string>, preview: string|null}>
     */
    protected array $templates = [];

    /**
     * @var array<string, string>
     */
    protected array $aliases = [];

    /**
     * @var array<string, string>
     */
    protected array $extensionPaths = [];

    protected ?string $applied = null;

    protected ?string $configuredAtApply = null;

    public function __construct(protected Application $app) {}

    /**
     * Register every template directory found in a views directory.
     */
    public function discover(string $directory): void
    {
        foreach (glob(rtrim($directory, '/\\').'/*', GLOB_ONLYDIR) ?: [] as $path) {
            if (is_file($path.'/template.json') || is_file($path.'/index.blade.php')) {
                $this->register(basename($path), $path);
            }
        }
    }

    /**
     * Register a template.
     *
     * @param  array<string, mixed>  $manifest  overrides for the values in `<path>/template.json`
     */
    public function register(string $name, string $path, array $manifest = []): void
    {
        $file = $path.'/template.json';
        $fromFile = is_file($file) ? (json_decode((string) file_get_contents($file), true) ?: []) : [];
        $manifest = array_merge($fromFile, $manifest);

        $this->templates[$name] = [
            'name' => $name,
            'path' => $path,
            'label' => (string) Arr::get($manifest, 'label', Str::headline($name)),
            'description' => (string) Arr::get($manifest, 'description', ''),
            'framework' => (string) Arr::get($manifest, 'framework', ''),
            'aliases' => (array) Arr::get($manifest, 'aliases', []),
            'extends' => Arr::get($manifest, 'extends'),
            'swatches' => (array) Arr::get($manifest, 'swatches', []),
            'css' => (array) Arr::get($manifest, 'css', []),
            'js' => (array) Arr::get($manifest, 'js', []),
            'preview' => Arr::get($manifest, 'preview'),
        ];

        foreach ($this->templates[$name]['aliases'] as $alias) {
            $this->aliases[$alias] = $name;
        }
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function all(): array
    {
        return $this->templates;
    }

    /**
     * @return array<int, string>
     */
    public function names(): array
    {
        return array_keys($this->templates);
    }

    public function has(?string $name): bool
    {
        return $name !== null && isset($this->templates[$this->aliases[$name] ?? $name]);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function get(string $name): ?array
    {
        return $this->templates[$this->resolve($name)] ?? null;
    }

    /**
     * Map a name or legacy alias to a registered template, falling back to the base template.
     */
    public function resolve(?string $name): string
    {
        $name = $this->aliases[$name] ?? $name;

        if ($name !== null && isset($this->templates[$name])) {
            return $name;
        }

        if (isset($this->templates[self::BASE_TEMPLATE])) {
            return self::BASE_TEMPLATE;
        }

        return (string) array_key_first($this->templates);
    }

    /**
     * Template set in config/admin.php.
     */
    public function configured(): string
    {
        return $this->resolve(config('admin.template', 'tailwind-light'));
    }

    /**
     * Template chosen in the admin panel, or null when none was chosen.
     */
    public function stored(): ?string
    {
        if (! $this->switcherEnabled()) {
            return null;
        }

        try {
            $value = $this->settingModel()::query()->where('key', self::SETTING_KEY)->value('value');
        } catch (Throwable) {
            return null;
        }

        return $this->has($value) ? $this->resolve($value) : null;
    }

    /**
     * Text of the templates page, from `admin.templates.*` in the app's language
     * files, or the package's English text when the app's published file predates it.
     *
     * @param  array<string, string|int>  $replace
     */
    public function text(string $key, array $replace = []): string
    {
        if (Lang::has('admin.templates.'.$key)) {
            return (string) trans('admin.templates.'.$key, $replace);
        }

        static $fallback;
        $fallback ??= (require __DIR__.'/../../resources/lang/en/admin.php')['templates'] ?? [];

        $line = $fallback[$key] ?? 'admin.templates.'.$key;

        foreach ($replace as $name => $value) {
            $line = str_replace(':'.$name, (string) $value, $line);
        }

        return $line;
    }

    /**
     * Template used for the current request.
     *
     * Changing `admin.template` at runtime (for example in app/Admin/bootstrap.php)
     * switches to that template.
     */
    public function active(): string
    {
        if ($this->applied !== null && $this->configured() !== $this->configuredAtApply) {
            return $this->apply($this->configured());
        }

        return $this->applied ?? $this->configured();
    }

    /**
     * Whether templates can be switched from the admin panel.
     */
    public function switcherEnabled(): bool
    {
        return (bool) config('admin.template_switcher', true);
    }

    /**
     * Whether the settings table exists, so a choice made in the admin panel can be saved.
     */
    public function canStore(): bool
    {
        try {
            $model = $this->settingModel();
            $instance = new $model;

            return $instance->getConnection()->getSchemaBuilder()->hasTable($instance->getTable());
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * Save the template chosen in the admin panel and use it for the rest of the request.
     */
    public function activate(string $name): void
    {
        $name = $this->resolve($name);

        $this->settingModel()::query()->updateOrCreate(
            ['key' => self::SETTING_KEY],
            ['value' => $name]
        );

        $this->apply($name);
    }

    /**
     * Forget the template chosen in the admin panel, so config/admin.php applies again.
     */
    public function forget(): void
    {
        $this->settingModel()::query()->where('key', self::SETTING_KEY)->delete();

        $this->apply($this->configured());
    }

    /**
     * Point the `admin::` namespace and every extension namespace at a template.
     *
     * Without a name the template chosen in the admin panel is used, then the configured one.
     */
    public function apply(?string $name = null): string
    {
        $name = $name === null ? ($this->stored() ?? $this->configured()) : $this->resolve($name);
        $this->configuredAtApply = $this->configured();

        if ($name === $this->applied) {
            return $name;
        }

        $this->applied = $name;

        $view = $this->app->make('view');
        $view->replaceNamespace('admin', $this->adminViewPaths($name));

        foreach ($this->extensionPaths as $namespace => $path) {
            $view->replaceNamespace($namespace, $this->extensionViewPaths($namespace, $path, $name));
        }

        $view->getFinder()->flush();

        return $name;
    }

    /**
     * Register an extension's views so that every template can restyle them.
     */
    public function extensionViews(string $namespace, string $path): void
    {
        $this->extensionPaths[$namespace] = rtrim($path, '/\\');

        $this->app->make('view')->replaceNamespace(
            $namespace,
            $this->extensionViewPaths($namespace, $this->extensionPaths[$namespace], $this->active())
        );
        $this->app->make('view')->getFinder()->flush();
    }

    /**
     * The template followed by the templates it extends.
     *
     * @return array<int, string>
     */
    public function lineage(?string $name = null): array
    {
        $name = $this->resolve($name ?? $this->active());
        $lineage = [];

        while ($name !== null && isset($this->templates[$name]) && ! in_array($name, $lineage, true)) {
            $lineage[] = $name;
            $parent = $this->templates[$name]['extends'];
            $name = $parent === null ? null : ($this->aliases[$parent] ?? $parent);
        }

        return $lineage;
    }

    /**
     * @return array<int, string>
     */
    public function adminViewPaths(?string $name = null): array
    {
        $name = $this->resolve($name ?? $this->active());
        $paths = [];

        foreach ($this->appViewPaths() as $appPath) {
            $paths[] = $appPath.'/vendor/admin/'.$name;
            $paths[] = $appPath.'/vendor/admin';
        }

        $lineage = $this->lineage($name);

        if (isset($this->templates[self::BASE_TEMPLATE])) {
            $lineage[] = self::BASE_TEMPLATE;
        }

        foreach (array_unique($lineage) as $template) {
            $paths[] = $this->templates[$template]['path'];
        }

        return $this->existing($paths);
    }

    /**
     * @return array<int, string>
     */
    public function extensionViewPaths(string $namespace, string $path, ?string $name = null): array
    {
        $name = $this->resolve($name ?? $this->active());
        $paths = [];

        foreach ($this->appViewPaths() as $appPath) {
            $paths[] = $appPath.'/vendor/'.$namespace.'/'.$name;
            $paths[] = $appPath.'/vendor/'.$namespace;
        }

        foreach ($this->lineage($name) as $template) {
            $paths[] = $this->templates[$template]['path'].'/extensions/'.$namespace;
            $paths[] = $path.'/'.$template;
        }

        $paths[] = $path;

        return $this->existing($paths);
    }

    /**
     * Base stylesheets of a template.
     *
     * @return array<int, string>
     */
    public function css(?string $name = null): array
    {
        return $this->get($name ?? $this->active())['css'] ?? [];
    }

    /**
     * Base scripts of a template.
     *
     * @return array<int, string>
     */
    public function js(?string $name = null): array
    {
        return $this->get($name ?? $this->active())['js'] ?? [];
    }

    /**
     * Views of the base template that a template does not provide itself.
     *
     * @return array<int, string>
     */
    public function missingViews(string $name): array
    {
        $template = $this->get($name);
        $base = $this->templates[self::BASE_TEMPLATE] ?? null;

        if ($template === null || $base === null) {
            return [];
        }

        $own = [];
        foreach ($this->lineage($template['name']) as $item) {
            $own = array_merge($own, $this->bladeFiles($this->templates[$item]['path']));
        }

        return array_values(array_diff($this->bladeFiles($base['path']), $own));
    }

    /**
     * @return array<int, string>
     */
    protected function bladeFiles(string $path): array
    {
        $files = [];
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS));

        foreach ($iterator as $file) {
            $relative = str_replace('\\', '/', substr($file->getPathname(), strlen($path) + 1));

            if (str_ends_with($relative, '.blade.php') && ! str_starts_with($relative, 'extensions/')) {
                $files[] = $relative;
            }
        }

        sort($files);

        return $files;
    }

    /**
     * @return class-string<Model>
     */
    protected function settingModel(): string
    {
        return config('admin.database.settings_model', Setting::class);
    }

    /**
     * @return array<int, string>
     */
    protected function appViewPaths(): array
    {
        return array_map(fn ($path) => rtrim($path, '/\\'), (array) config('view.paths', []));
    }

    /**
     * @param  array<int, string>  $paths
     * @return array<int, string>
     */
    protected function existing(array $paths): array
    {
        return array_values(array_unique(array_filter($paths, 'is_dir')));
    }
}
