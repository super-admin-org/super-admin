<?php

namespace SuperAdmin\Admin\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use SuperAdmin\Admin\Auth\TwoFactor\TwoFactor;
use SuperAdmin\Admin\Facades\Admin;
use SuperAdmin\Admin\Layout\Content;

/**
 * User menu > Two-factor: turn two-factor authentication on or off for your own
 * account, and (for administrators) choose the panel-wide mode.
 */
class TwoFactorController extends Controller
{
    public function index(Content $content): Content
    {
        $twoFactor = Admin::twoFactor();
        $user = Admin::user();
        $secret = $twoFactor->isPending($user) ? $twoFactor->secret($user) : null;
        $userModel = config('admin.database.users_model');
        $canManagePolicy = $twoFactor->userCan('PUT', 'auth/two-factor/policy');

        return $content
            ->title($twoFactor->text('title'))
            ->description($twoFactor->text('description'))
            ->body(view('admin::two-factor', [
                'mode' => $twoFactor->mode(),
                'configuredMode' => $twoFactor->configuredMode(),
                'storedMode' => $twoFactor->storedMode(),
                'canStore' => $twoFactor->canStore(),
                'enabled' => $twoFactor->enabledFor($user),
                'pending' => $secret !== null,
                'secret' => $secret,
                'qrCode' => $secret !== null ? $twoFactor->qrCode($user, $secret) : null,
                'recoveryCodes' => (array) session(TwoFactor::NEW_RECOVERY_CODES, []),
                'recoveryCodesLeft' => $twoFactor->enabledFor($user) ? $twoFactor->recoveryCodesLeft($user) : 0,
                'canManagePolicy' => $canManagePolicy,
                'panelEnabled' => $twoFactor->panelEnabled(),
                'canStoreMode' => $twoFactor->canStoreMode(),
                'usersEnabled' => $canManagePolicy && $twoFactor->canStore() ? $userModel::query()->whereNotNull('two_factor_confirmed_at')->count() : 0,
                'usersTotal' => $canManagePolicy ? $userModel::query()->count() : 0,
            ]));
    }

    /**
     * Start the setup: create a secret and show its QR code.
     */
    public function store(): RedirectResponse
    {
        $twoFactor = Admin::twoFactor();
        $user = Admin::user();

        if ($response = $this->refuseWhenUnavailable($twoFactor)) {
            return $response;
        }

        if (! $twoFactor->enabledFor($user)) {
            $twoFactor->begin($user);
        }

        return redirect()->route('admin.two-factor');
    }

    /**
     * Finish the setup with a code from the authenticator app.
     */
    public function update(Request $request): RedirectResponse
    {
        $twoFactor = Admin::twoFactor();
        $user = Admin::user();

        if ($response = $this->refuseWhenUnavailable($twoFactor)) {
            return $response;
        }

        $request->validate(['code' => ['required', 'string']]);

        if (! $twoFactor->confirm($user, (string) $request->input('code'))) {
            return redirect()->route('admin.two-factor')->withErrors(['code' => $twoFactor->text('invalid_code')]);
        }

        session()->flash(TwoFactor::NEW_RECOVERY_CODES, $twoFactor->generateRecoveryCodes($user));
        admin_toastr($twoFactor->text('enabled'));

        return redirect()->route('admin.two-factor');
    }

    /**
     * Turn two-factor off, or cancel a setup that was not confirmed.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $twoFactor = Admin::twoFactor();
        $user = Admin::user();

        if ($twoFactor->enabledFor($user)) {
            if ($twoFactor->mode() === 'required') {
                admin_toastr($twoFactor->text('required_cannot_disable'), 'error');

                return redirect()->route('admin.two-factor');
            }

            if ($response = $this->refuseWrongPassword($request)) {
                return $response;
            }
        }

        $twoFactor->disable($user);
        admin_toastr($twoFactor->text('disabled'));

        return redirect()->route('admin.two-factor');
    }

    /**
     * Replace the recovery codes; the old ones stop working.
     */
    public function recoveryCodes(Request $request): RedirectResponse
    {
        $twoFactor = Admin::twoFactor();
        $user = Admin::user();

        if (! $twoFactor->enabledFor($user)) {
            return redirect()->route('admin.two-factor');
        }

        if ($response = $this->refuseWrongPassword($request)) {
            return $response;
        }

        session()->flash(TwoFactor::NEW_RECOVERY_CODES, $twoFactor->generateRecoveryCodes($user));
        admin_toastr($twoFactor->text('codes_regenerated'));

        return redirect()->route('admin.two-factor');
    }

    /**
     * Change the panel-wide mode (off, optional, required).
     */
    public function updatePolicy(Request $request): RedirectResponse
    {
        $twoFactor = Admin::twoFactor();

        if ($response = $this->refusePolicyChange($twoFactor)) {
            return $response;
        }

        $validated = $request->validate(
            ['mode' => ['required', 'string', Rule::in(TwoFactor::MODES)]],
            ['mode.in' => $twoFactor->text('unknown_mode')]
        );

        $twoFactor->setMode($validated['mode']);
        admin_toastr($twoFactor->text('mode_saved', ['mode' => $twoFactor->text('mode_'.$validated['mode'])]));

        return redirect()->route('admin.two-factor');
    }

    /**
     * Forget the mode chosen in the panel, so config/admin.php applies again.
     */
    public function destroyPolicy(): RedirectResponse
    {
        $twoFactor = Admin::twoFactor();

        if ($response = $this->refusePolicyChange($twoFactor)) {
            return $response;
        }

        $twoFactor->forgetMode();
        admin_toastr($twoFactor->text('mode_reset', ['mode' => $twoFactor->text('mode_'.$twoFactor->configuredMode())]));

        return redirect()->route('admin.two-factor');
    }

    protected function refuseWhenUnavailable(TwoFactor $twoFactor): ?RedirectResponse
    {
        if (! $twoFactor->canStore()) {
            admin_toastr($twoFactor->text('not_migrated'), 'error');
        } elseif ($twoFactor->mode() === 'off') {
            admin_toastr($twoFactor->text('mode_off_notice'), 'error');
        } else {
            return null;
        }

        return redirect()->route('admin.two-factor');
    }

    protected function refuseWrongPassword(Request $request): ?RedirectResponse
    {
        $request->validate(['password' => ['required', 'string']]);

        if (Hash::check((string) $request->input('password'), (string) Admin::user()->getAuthPassword())) {
            return null;
        }

        return redirect()->route('admin.two-factor')
            ->withErrors(['password' => Admin::twoFactor()->text('wrong_password')]);
    }

    protected function refusePolicyChange(TwoFactor $twoFactor): ?RedirectResponse
    {
        if (! $twoFactor->panelEnabled()) {
            admin_toastr($twoFactor->text('panel_disabled'), 'error');
        } elseif (! $twoFactor->canStoreMode()) {
            admin_toastr($twoFactor->text('settings_not_migrated'), 'error');
        } else {
            return null;
        }

        return redirect()->route('admin.two-factor');
    }
}
