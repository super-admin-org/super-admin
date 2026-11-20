<?php

namespace SuperAdmin\Admin\Console;

use Illuminate\Console\Command;
use SuperAdmin\Admin\Auth\TwoFactor\TwoFactor;
use SuperAdmin\Admin\Facades\Admin;

class TwoFactorCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'admin:two-factor
        {--mode= : Set the panel-wide mode: off, optional or required}
        {--reset : Forget the mode chosen in the admin panel and use config/admin.php}
        {--disable= : Turn two-factor off for this username (for example after a lost phone)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show or change two-factor authentication for the admin panel';

    public function handle(): int
    {
        $twoFactor = Admin::twoFactor();

        if (! $twoFactor->canStore()) {
            $this->error($twoFactor->text('not_migrated'));

            return self::FAILURE;
        }

        if ($username = $this->option('disable')) {
            return $this->disableFor((string) $username);
        }

        if ($this->option('reset')) {
            if (! $twoFactor->canStoreMode()) {
                $this->error($twoFactor->text('settings_not_migrated'));

                return self::FAILURE;
            }

            $twoFactor->forgetMode();
        } elseif (($mode = $this->option('mode')) !== null) {
            if (! in_array($mode, TwoFactor::MODES, true)) {
                $this->error($twoFactor->text('unknown_mode'));

                return self::FAILURE;
            }

            if (! $twoFactor->canStoreMode()) {
                $this->error($twoFactor->text('settings_not_migrated'));

                return self::FAILURE;
            }

            $twoFactor->setMode($mode);
        }

        $this->status($twoFactor);

        return self::SUCCESS;
    }

    protected function disableFor(string $username): int
    {
        $userModel = config('admin.database.users_model');
        $user = $userModel::query()->where('username', $username)->first();

        if (! $user) {
            $this->error("No admin user named [{$username}].");

            return self::FAILURE;
        }

        Admin::twoFactor()->disable($user);

        $this->info(Admin::twoFactor()->text('reset_done', ['name' => $username]));

        return self::SUCCESS;
    }

    protected function status(TwoFactor $twoFactor): void
    {
        $userModel = config('admin.database.users_model');
        $stored = $twoFactor->storedMode();

        $this->table(['Setting', 'Value'], [
            ['Mode', $twoFactor->mode()],
            ['Set by', $stored !== null ? 'admin panel (admin_settings)' : 'config/admin.php (two_factor.mode)'],
            ['Panel may change it', $twoFactor->panelEnabled() ? 'yes' : 'no (two_factor.panel is false)'],
            ['Users with two-factor', $userModel::query()->whereNotNull('two_factor_confirmed_at')->count().' of '.$userModel::query()->count()],
        ]);
    }
}
