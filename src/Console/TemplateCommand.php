<?php

namespace SuperAdmin\Admin\Console;

use Illuminate\Console\Command;
use SuperAdmin\Admin\Facades\Admin;

class TemplateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'admin:template
        {template? : Template to activate (stored in the settings table, like the admin panel does)}
        {--reset : Forget the stored template so config/admin.php applies again}
        {--menu : Add the "Templates" item to the Admin menu and the auth/templates path to the "Auth management" permission}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List the admin templates, switch the active one, or add the Templates menu item';

    public function handle(): int
    {
        $templates = Admin::template();

        if ($this->option('menu')) {
            $this->installMenu();
        }

        if ($this->option('reset')) {
            if (! $templates->canStore()) {
                $this->error(Admin::template()->text('not_migrated'));

                return self::FAILURE;
            }

            $templates->forget();
            $this->info('Stored template removed; using '.$templates->configured().' from config/admin.php.');
        }

        if ($name = $this->argument('template')) {
            if (! $templates->has($name)) {
                $this->error("Template [{$name}] is not installed. Available: ".implode(', ', $templates->names()));

                return self::FAILURE;
            }

            if (! $templates->canStore()) {
                $this->error(Admin::template()->text('not_migrated'));

                return self::FAILURE;
            }

            $templates->activate($name);
            $this->info('Active template: '.$templates->resolve($name));
        }

        $stored = $templates->stored();
        $active = $stored ?? $templates->configured();

        $rows = [];
        foreach ($templates->all() as $template) {
            $missing = count($templates->missingViews($template['name']));
            $rows[] = [
                $template['name'] === $active ? '*' : '',
                $template['name'],
                $template['label'],
                $template['framework'],
                $missing ? $missing.' missing' : 'complete',
            ];
        }

        $this->table(['', 'Name', 'Label', 'Framework', 'Views'], $rows);
        $this->line('Active: <info>'.$active.'</info> ('.($stored ? 'chosen in the admin panel' : 'config/admin.php').')');

        return self::SUCCESS;
    }

    protected function installMenu(): void
    {
        $menuModel = config('admin.database.menu_model');
        $permissionModel = config('admin.database.permissions_model');

        if ($menuModel::query()->where('uri', 'auth/templates')->exists()) {
            $this->line('Menu item "Templates" already exists.');
        } else {
            $parent = $menuModel::query()->where('uri', 'auth/menu')->value('parent_id') ?? 0;

            $menuModel::query()->create([
                'parent_id' => $parent,
                'order' => (int) $menuModel::query()->max('order') + 1,
                'title' => 'Templates',
                'icon' => 'icon-palette',
                'uri' => 'auth/templates',
            ]);
            $this->info('Menu item "Templates" added.');
        }

        $permission = $permissionModel::query()->where('slug', 'auth.management')->first();

        if ($permission && ! str_contains((string) $permission->http_path, '/auth/templates')) {
            $permission->http_path = rtrim((string) $permission->http_path)."\r\n/auth/templates";
            $permission->save();
            $this->info('Path /auth/templates added to the "Auth management" permission.');
        }
    }
}
