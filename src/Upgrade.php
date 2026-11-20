<?php

namespace SuperAdmin\Admin;

use Illuminate\Support\Str;
use ReflectionClass;
use Throwable;

/**
 * What an update of super-admin (or one of its extensions) still needs:
 * published assets older than the installed package, and migrations not run yet.
 *
 * `php artisan admin:upgrade` does both; administrators see a notice until then.
 */
class Upgrade
{
    /**
     * File in resources/assets (published to public/vendor/super-admin) holding the assets' version.
     */
    public const VERSION_FILE = 'version.txt';

    /**
     * Migration folders of super-admin and of the registered extensions.
     *
     * @return array<int, string>
     */
    public function migrationPaths(): array
    {
        $paths = [realpath(__DIR__.'/../database/migrations')];

        foreach (Admin::$extensions as $class) {
            try {
                $path = is_string($class) && class_exists($class) ? app($class)->migrations() : null;
            } catch (Throwable) {
                continue;
            }

            if (is_string($path) && $path !== '' && is_dir($path)) {
                $paths[] = realpath($path);
            }
        }

        return array_values(array_unique(array_filter($paths)));
    }

    /**
     * Names of the super-admin and extension migrations that have not run.
     *
     * @return array<int, string>
     */
    public function pendingMigrations(): array
    {
        return array_keys($this->pendingMigrationFiles());
    }

    /**
     * The file to run for each pending migration, by name.
     *
     * Older installs published copies to database/migrations. A copy (or a file whose
     * class is already loaded) is preferred over the package file, so a named migration
     * class is never declared twice.
     *
     * @return array<string, string>
     */
    public function pendingMigrationFiles(): array
    {
        $files = [];

        foreach ($this->migrationPaths() as $path) {
            foreach (glob($path.'/*_*.php') ?: [] as $file) {
                $files[basename($file, '.php')] ??= $file;
            }
        }

        if ($files === []) {
            return [];
        }

        try {
            $repository = app('migration.repository');

            $ran = $repository->repositoryExists() ? $repository->getRan() : [];
        } catch (Throwable) {
            return [];
        }

        $pending = array_diff_key($files, array_flip($ran));
        ksort($pending);

        foreach ($pending as $name => $file) {
            $pending[$name] = $this->preferredFile($name, $file);
        }

        return $pending;
    }

    protected function preferredFile(string $name, string $file): string
    {
        $class = Str::studly(implode('_', array_slice(explode('_', $name), 4)));

        if ($class !== '' && class_exists($class, false)) {
            $loaded = (new ReflectionClass($class))->getFileName();

            if ($loaded && str_ends_with(str_replace('\\', '/', $loaded), '/'.$name.'.php')) {
                return $loaded;
            }
        }

        $copy = database_path('migrations/'.$name.'.php');

        return is_file($copy) ? $copy : $file;
    }

    /**
     * Version of the assets in public/vendor/super-admin, or null when they predate the version file.
     */
    public function publishedVersion(): ?string
    {
        $file = public_path('vendor/super-admin/'.self::VERSION_FILE);

        if (! is_file($file)) {
            return null;
        }

        return trim((string) file_get_contents($file)) ?: null;
    }

    /**
     * Version of the assets shipped with the installed package.
     */
    public function packageVersion(): string
    {
        $file = __DIR__.'/../resources/assets/'.self::VERSION_FILE;

        return is_file($file) ? trim((string) file_get_contents($file)) : Admin::VERSION;
    }

    public function assetsOutdated(): bool
    {
        return $this->publishedVersion() !== $this->packageVersion();
    }

    /**
     * Whether `php artisan admin:upgrade` has something to do.
     */
    public function needed(): bool
    {
        return $this->assetsOutdated() || $this->pendingMigrations() !== [];
    }

    /**
     * What to tell the signed-in administrator, or null when nothing is needed
     * (or the user is not an administrator, or `admin.upgrade_notice` is false).
     *
     * @return array{assets: bool, published: string|null, installed: string, migrations: int, hook: bool}|null
     */
    public function notice(): ?array
    {
        if (! config('admin.upgrade_notice', true)) {
            return null;
        }

        $user = app(Admin::class)->user();

        if (! $user || ! method_exists($user, 'isAdministrator') || ! $user->isAdministrator()) {
            return null;
        }

        $assets = $this->assetsOutdated();
        $migrations = count($this->pendingMigrations());

        if (! $assets && $migrations === 0) {
            return null;
        }

        return [
            'assets' => $assets,
            'published' => $this->publishedVersion(),
            'installed' => $this->packageVersion(),
            'migrations' => $migrations,
            'hook' => $this->composerHookInstalled(),
        ];
    }

    /**
     * Whether the app's composer.json runs `admin:upgrade` after `composer update`.
     */
    public function composerHookInstalled(): bool
    {
        $composer = json_decode((string) @file_get_contents(base_path('composer.json')), true);
        $scripts = (array) ($composer['scripts']['post-update-cmd'] ?? []);

        foreach ($scripts as $script) {
            if (is_string($script) && str_contains($script, 'admin:upgrade')) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<string, string|int>  $replace
     */
    public function text(string $key, array $replace = []): string
    {
        return admin_text('upgrade.'.$key, $replace);
    }
}
