<?php

namespace SuperAdmin\Admin\Console;

use Illuminate\Console\Command;
use SuperAdmin\Admin\Facades\Admin;

class UpgradeCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'admin:upgrade
        {--force : Run the migrations in production without asking}
        {--skip-assets : Do not publish the assets}
        {--skip-migrations : Do not run the migrations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finish an update of super-admin and its extensions: publish assets, run new migrations, clear compiled views';

    public function handle(): int
    {
        $upgrade = Admin::upgrade();
        $status = self::SUCCESS;

        if (! $this->option('skip-assets')) {
            $this->callSilent('vendor:publish', ['--tag' => ['super-admin-assets', 'laravel-assets'], '--force' => true]);
            $this->info($upgrade->text('assets_published', ['version' => $upgrade->packageVersion()]));
        }

        if (! $this->option('skip-migrations')) {
            $status = $this->migrate();
        }

        $this->callSilent('view:clear');
        $this->info($upgrade->text('views_cleared'));

        if (! $upgrade->composerHookInstalled()) {
            $this->line('');
            $this->line($upgrade->text('hook_tip'));
            $this->line('    "post-update-cmd": ["@php artisan admin:upgrade --no-interaction"]');
        }

        return $status;
    }

    protected function migrate(): int
    {
        $upgrade = Admin::upgrade();
        $pending = $upgrade->pendingMigrationFiles();

        if ($pending === []) {
            $this->info($upgrade->text('no_migrations'));

            return self::SUCCESS;
        }

        $this->line($upgrade->text('migrations_pending', ['count' => count($pending)]));

        foreach (array_keys($pending) as $migration) {
            $this->line('  - '.$migration);
        }

        if ($this->laravel->environment('production') && ! $this->option('force')
            && ! $this->confirm($upgrade->text('confirm_production'), false)) {
            $this->warn($upgrade->text('migrations_skipped'));

            return self::SUCCESS;
        }

        return $this->call('migrate', [
            '--path' => array_values($pending),
            '--realpath' => true,
            '--force' => true,
        ]) === 0 ? self::SUCCESS : self::FAILURE;
    }
}
