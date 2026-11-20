<?php

namespace SuperAdmin\Admin\Auth\TwoFactor;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use SuperAdmin\Admin\Auth\Database\Setting;
use SuperAdmin\Admin\Facades\Admin;
use Throwable;

/**
 * Two-factor authentication for admin users: after the password, ask for a
 * 6-digit code from an authenticator app (or a one-time recovery code).
 *
 * Who is asked depends on the mode:
 *   off       never ask for a code
 *   optional  ask users who turned it on (User menu > Two-factor)
 *   required  ask everybody; users without it must set it up before using the panel
 *
 * The mode comes from `admin.two_factor.mode` in config/admin.php, unless an
 * administrator changed it in the admin panel (stored in the settings table).
 */
class TwoFactor
{
    public const MODES = ['off', 'optional', 'required'];

    /**
     * Key of the mode in the settings table.
     */
    public const SETTING_KEY = 'two_factor';

    /**
     * Session key of a login waiting for its code.
     */
    public const PENDING_LOGIN = 'admin.two_factor.login';

    /**
     * Session key of the recovery codes shown once after they were generated.
     */
    public const NEW_RECOVERY_CODES = 'admin.two_factor.recovery_codes';

    /**
     * Seconds a password-checked login waits for its code.
     */
    public const PENDING_LOGIN_LIFETIME = 300;

    protected ?bool $canStore = null;

    /**
     * Mode set in config/admin.php.
     */
    public function configuredMode(): string
    {
        return $this->normalizeMode(config('admin.two_factor.mode', 'optional')) ?? 'optional';
    }

    /**
     * Mode chosen in the admin panel, or null when none was chosen.
     */
    public function storedMode(): ?string
    {
        if (! $this->panelEnabled()) {
            return null;
        }

        try {
            $value = $this->settingModel()::query()->where('key', self::SETTING_KEY)->value('value');
        } catch (Throwable) {
            return null;
        }

        return $this->normalizeMode($value);
    }

    /**
     * Mode used now. Two-factor stays off until the users table has its columns.
     */
    public function mode(): string
    {
        if (! $this->canStore()) {
            return 'off';
        }

        return $this->storedMode() ?? $this->configuredMode();
    }

    /**
     * Whether administrators may change the mode in the admin panel.
     */
    public function panelEnabled(): bool
    {
        return (bool) config('admin.two_factor.panel', true);
    }

    /**
     * Whether the settings table exists, so a mode chosen in the panel can be saved.
     */
    public function canStoreMode(): bool
    {
        return Admin::template()->canStore();
    }

    /**
     * Save the mode chosen in the admin panel.
     */
    public function setMode(string $mode): void
    {
        $mode = $this->normalizeMode($mode) ?? 'optional';

        $this->settingModel()::query()->updateOrCreate(['key' => self::SETTING_KEY], ['value' => $mode]);
    }

    /**
     * Forget the mode chosen in the admin panel, so config/admin.php applies again.
     */
    public function forgetMode(): void
    {
        $this->settingModel()::query()->where('key', self::SETTING_KEY)->delete();
    }

    /**
     * Whether the users table has the two-factor columns (the migration ran).
     */
    public function canStore(): bool
    {
        if ($this->canStore !== null) {
            return $this->canStore;
        }

        try {
            $model = config('admin.database.users_model');
            $instance = new $model;
            $schema = $instance->getConnection()->getSchemaBuilder();

            return $this->canStore = $schema->hasColumns($instance->getTable(), ['two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at']);
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * Whether a user finished setting up two-factor authentication.
     */
    public function enabledFor(?object $user): bool
    {
        return $user instanceof Model
            && ! empty($user->getAttribute('two_factor_secret'))
            && ! empty($user->getAttribute('two_factor_confirmed_at'));
    }

    /**
     * Whether a user scanned a QR code but did not confirm it with a code yet.
     */
    public function isPending(?object $user): bool
    {
        return $user instanceof Model
            && ! empty($user->getAttribute('two_factor_secret'))
            && empty($user->getAttribute('two_factor_confirmed_at'));
    }

    /**
     * Whether a user must type a code after the password.
     */
    public function requiresChallenge(?object $user): bool
    {
        return $this->enabledFor($user) && $this->mode() !== 'off';
    }

    /**
     * Whether a signed-in user has to set up two-factor before using the panel.
     */
    public function mustSetUp(?object $user): bool
    {
        return $user instanceof Model && ! $this->enabledFor($user) && $this->mode() === 'required';
    }

    /**
     * Start the setup: give the user a new secret to scan. It is used once confirmed.
     */
    public function begin(Model $user): string
    {
        $secret = Totp::generateSecret();

        $user->forceFill([
            'two_factor_secret' => Crypt::encryptString($secret),
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        return $secret;
    }

    /**
     * Finish the setup with a code from the app.
     */
    public function confirm(Model $user, string $code): bool
    {
        if (! $this->isPending($user) || ! $this->verify($user, $code)) {
            return false;
        }

        $user->forceFill(['two_factor_confirmed_at' => $user->freshTimestamp()])->save();

        return true;
    }

    /**
     * Turn two-factor off for a user and forget the secret and recovery codes.
     */
    public function disable(Model $user): void
    {
        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();
    }

    /**
     * The user's secret, or null when there is none or it cannot be decrypted (APP_KEY changed).
     */
    public function secret(Model $user): ?string
    {
        $encrypted = $user->getAttribute('two_factor_secret');

        if (empty($encrypted)) {
            return null;
        }

        try {
            return Crypt::decryptString($encrypted);
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Check a code from the authenticator app. A code is accepted once.
     */
    public function verify(Model $user, string $code): bool
    {
        $secret = $this->secret($user);

        if ($secret === null) {
            return false;
        }

        $timestep = Totp::verify($secret, $code, max(0, (int) config('admin.two_factor.window', 1)));

        if ($timestep === null) {
            return false;
        }

        return $this->markCodeUsed($user, $secret, $timestep);
    }

    /**
     * Use a recovery code instead of a code from the app. Each recovery code works once.
     */
    public function useRecoveryCode(Model $user, string $code): bool
    {
        $code = strtolower(trim($code));
        $hashes = $this->recoveryCodeHashes($user);

        foreach ($hashes as $index => $hash) {
            if ($code !== '' && Hash::check($code, $hash)) {
                unset($hashes[$index]);

                $user->forceFill(['two_factor_recovery_codes' => json_encode(array_values($hashes))])->save();

                return true;
            }
        }

        return false;
    }

    /**
     * Replace the user's recovery codes. Only hashes are stored, so show the result once.
     *
     * @return array<int, string>
     */
    public function generateRecoveryCodes(Model $user): array
    {
        $codes = [];

        for ($i = 0; $i < max(1, (int) config('admin.two_factor.recovery_codes', 8)); $i++) {
            $codes[] = strtolower(Str::random(5).'-'.Str::random(5));
        }

        $user->forceFill([
            'two_factor_recovery_codes' => json_encode(array_map(fn ($code) => Hash::make($code), $codes)),
        ])->save();

        return $codes;
    }

    public function recoveryCodesLeft(Model $user): int
    {
        return count($this->recoveryCodeHashes($user));
    }

    /**
     * The name shown in the authenticator app.
     */
    public function issuer(): string
    {
        $issuer = config('admin.two_factor.issuer') ?: config('admin.name') ?: config('app.name') ?: 'Super Admin';

        return trim(html_entity_decode(strip_tags((string) $issuer), ENT_QUOTES)) ?: 'Super Admin';
    }

    /**
     * The otpauth:// link for a secret.
     */
    public function uri(Model $user, string $secret): string
    {
        $account = (string) ($user->getAttribute('username') ?: $user->getAuthIdentifier());

        return Totp::uri($this->issuer(), $account, $secret);
    }

    /**
     * The QR code (SVG) of a secret.
     */
    public function qrCode(Model $user, string $secret): string
    {
        return QrCode::svg($this->uri($user, $secret));
    }

    /**
     * Whether the signed-in user may open an admin path, following the role permissions.
     */
    public function userCan(string $method, string $path): bool
    {
        $user = Admin::user();

        if (! $user) {
            return false;
        }

        if (config('admin.check_route_permission') === false) {
            return true;
        }

        if (method_exists($user, 'isAdministrator') && $user->isAdministrator()) {
            return true;
        }

        if (! method_exists($user, 'allPermissions')) {
            return false;
        }

        $request = Request::create(admin_url($path), $method);

        return (bool) $user->allPermissions()->first(fn ($permission) => $permission->shouldPassThrough($request));
    }

    /**
     * Text from `admin.two_factor.*` in the app's language files, or the package's
     * English text when the app's published file predates two-factor authentication.
     *
     * @param  array<string, string|int>  $replace
     */
    public function text(string $key, array $replace = []): string
    {
        return admin_text('two_factor.'.$key, $replace);
    }

    /**
     * @return array<int, string>
     */
    protected function recoveryCodeHashes(Model $user): array
    {
        $hashes = json_decode((string) $user->getAttribute('two_factor_recovery_codes'), true);

        return is_array($hashes) ? array_values(array_filter($hashes, 'is_string')) : [];
    }

    /**
     * Remember a used time step, so a code seen by someone else cannot be replayed.
     */
    protected function markCodeUsed(Model $user, string $secret, int $timestep): bool
    {
        $key = 'admin-two-factor-used:'.$user->getKey().':'.substr(hash('sha256', $secret), 0, 16).':'.$timestep;
        $seconds = Totp::PERIOD * (2 * max(0, (int) config('admin.two_factor.window', 1)) + 2);

        try {
            return Cache::add($key, true, $seconds);
        } catch (Throwable) {
            return true;
        }
    }

    protected function normalizeMode(mixed $mode): ?string
    {
        if ($mode === true || $mode === 'true' || $mode === '1' || $mode === 1) {
            return 'optional';
        }

        if ($mode === false || $mode === 'false' || $mode === '0' || $mode === 0) {
            return 'off';
        }

        $mode = is_string($mode) ? strtolower(trim($mode)) : null;

        return in_array($mode, self::MODES, true) ? $mode : null;
    }

    /**
     * @return class-string<Model>
     */
    protected function settingModel(): string
    {
        return config('admin.database.settings_model', Setting::class);
    }
}
