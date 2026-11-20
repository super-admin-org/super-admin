<?php

namespace SuperAdmin\Admin\Console;

use Illuminate\Console\Command;
use SuperAdmin\Admin\Template\Vocabulary;

class LintViewsCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'admin:lint-views
        {paths* : Files or directories to scan (Blade views, PHP and JS files)}
        {--ignore=* : Your own class names or prefixes to skip, e.g. --ignore=table-field- (a trailing dash means prefix)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Report CSS classes that only some admin templates style (use the shared vocabulary instead)';

    public function handle(): int
    {
        $findings = [];

        foreach ($this->files((array) $this->argument('paths')) as $file) {
            foreach ($this->lint($file) as $finding) {
                $findings[] = $finding;
            }
        }

        if ($findings === []) {
            $this->info('No template-specific classes found.');

            return self::SUCCESS;
        }

        $this->table(['File', 'Line', 'Class'], $findings);
        $this->warn(count($findings).' class(es) are not part of the shared vocabulary (SuperAdmin\Admin\Template\Vocabulary). Use a vocabulary class or a class prefixed with your extension name.');

        return self::FAILURE;
    }

    /**
     * @return array<int, array{0: string, 1: int, 2: string}>
     */
    protected function lint(string $file): array
    {
        $findings = [];
        $base = getcwd() ?: '';

        foreach (file($file) ?: [] as $index => $line) {
            if (! preg_match_all('/class\s*=\s*\\\\?["\']([^"\']*)/', $line, $matches)) {
                continue;
            }

            foreach ($matches[1] as $list) {
                $list = preg_replace('/\{\{.*?\}\}|\{!!.*?!!\}|\{\$[^}]*\}|\$\w+/', ' ', $list);

                foreach (preg_split('/\s+/', trim((string) $list)) ?: [] as $class) {
                    if ($class === '' || ! preg_match('/^[a-z][a-z0-9-]*[a-z0-9]$/', $class) || $this->ignored($class)) {
                        continue;
                    }

                    if (! Vocabulary::allows($class) && Vocabulary::looksLikeFramework($class)) {
                        $findings[] = [ltrim(str_replace([$base, '\\'], ['', '/'], $file), '/'), $index + 1, $class];
                    }
                }
            }
        }

        return $findings;
    }

    protected function ignored(string $class): bool
    {
        foreach ((array) $this->option('ignore') as $ignore) {
            if ($class === $ignore || (str_ends_with($ignore, '-') && str_starts_with($class, $ignore))) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<int, string>  $paths
     * @return array<int, string>
     */
    protected function files(array $paths): array
    {
        $files = [];

        foreach ($paths as $path) {
            if (is_file($path)) {
                $files[] = $path;

                continue;
            }

            if (! is_dir($path)) {
                $this->warn("Skipped missing path: {$path}");

                continue;
            }

            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS));

            foreach ($iterator as $file) {
                $name = $file->getPathname();

                if (preg_match('#[\\\\/](vendor|node_modules|\.git)[\\\\/]#', $name) || preg_match('/\.min\.js$/', $name)) {
                    continue;
                }

                if (preg_match('/\.(php|js)$/', $name)) {
                    $files[] = $name;
                }
            }
        }

        sort($files);

        return $files;
    }
}
