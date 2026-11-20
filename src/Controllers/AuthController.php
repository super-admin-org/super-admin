<?php

namespace SuperAdmin\Admin\Controllers;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use SuperAdmin\Admin\Auth\TwoFactor\TwoFactor;
use SuperAdmin\Admin\Facades\Admin;
use SuperAdmin\Admin\Form;
use SuperAdmin\Admin\Layout\Content;
use Symfony\Component\Translation\TranslatorInterface;

class AuthController extends Controller
{
    /**
     * @var string
     */
    protected $loginView = 'admin::login';

    /**
     * @var string
     */
    protected $twoFactorView = 'admin::two-factor-challenge';

    /**
     * Show the login page.
     *
     * @return Factory|Redirect|View
     */
    public function getLogin()
    {
        if ($this->guard()->check()) {
            return redirect($this->redirectPath());
        }

        return view($this->loginView);
    }

    /**
     * Handle a login request.
     *
     *
     * @return mixed
     */
    public function postLogin(Request $request)
    {
        $rate_limit_key = 'login-tries-'.Admin::guardName();

        $this->loginValidator($request->all())->validate();

        $credentials = $request->only([$this->username(), 'password']);
        $remember = (bool) $request->get('remember', false);
        $guard = $this->guard();

        if (! method_exists($guard, 'getLastAttempted')) {
            if ($guard->attempt($credentials, $remember)) {
                RateLimiter::clear($rate_limit_key);

                return $this->sendLoginResponse($request);
            }
        } elseif ($guard->validate($credentials)) {
            RateLimiter::clear($rate_limit_key);

            $user = $guard->getLastAttempted();

            if (Admin::twoFactor()->requiresChallenge($user)) {
                $request->session()->put(TwoFactor::PENDING_LOGIN, [
                    'id' => $user->getAuthIdentifier(),
                    'remember' => $remember,
                    'expires' => time() + TwoFactor::PENDING_LOGIN_LIFETIME,
                ]);

                return redirect(admin_url('auth/two-factor/challenge'));
            }

            $guard->login($user, $remember);

            return $this->sendLoginResponse($request);
        }

        if (config('admin.auth.throttle_logins')) {
            $throttle_timeout = config('admin.auth.throttle_timeout', 600);
            RateLimiter::hit($rate_limit_key, $throttle_timeout);
        }

        return back()->withInput()->withErrors([
            $this->username() => $this->getFailedLoginMessage(),
        ]);
    }

    /**
     * Show the page asking for the two-factor code after a correct password.
     *
     * @return Factory|Redirect|View
     */
    public function getTwoFactorChallenge(Request $request)
    {
        if ($this->guard()->check()) {
            return redirect($this->redirectPath());
        }

        if (! $user = $this->pendingTwoFactorUser($request)) {
            return redirect(admin_url('auth/login'));
        }

        return view($this->twoFactorView, [
            'account' => $user->getAttribute($this->username()) ?: $user->getAttribute('name'),
        ]);
    }

    /**
     * Check the two-factor code (or a recovery code) and finish the login.
     *
     * @return mixed
     */
    public function postTwoFactorChallenge(Request $request)
    {
        $twoFactor = Admin::twoFactor();
        $user = $this->pendingTwoFactorUser($request);

        if (! $user) {
            return redirect(admin_url('auth/login'))
                ->withErrors([$this->username() => $twoFactor->text('expired')]);
        }

        $rateLimitKey = 'two-factor-tries-'.Admin::guardName().'-'.$user->getAuthIdentifier();
        $timeout = (int) config('admin.auth.throttle_timeout', 900);

        if (RateLimiter::tooManyAttempts($rateLimitKey, (int) config('admin.auth.throttle_attempts', 5))) {
            $request->session()->forget(TwoFactor::PENDING_LOGIN);

            return redirect(admin_url('auth/login'))->withErrors([
                $this->username() => $twoFactor->text('too_many_attempts', [
                    'minutes' => max(1, (int) ceil(RateLimiter::availableIn($rateLimitKey) / 60)),
                ]),
            ]);
        }

        $code = trim((string) $request->input('code'));
        $recoveryCode = trim((string) $request->input('recovery_code'));
        $usedRecoveryCode = false;

        if ($code !== '') {
            $passed = $twoFactor->verify($user, $code);
        } else {
            $passed = $usedRecoveryCode = $recoveryCode !== '' && $twoFactor->useRecoveryCode($user, $recoveryCode);
        }

        if (! $passed) {
            RateLimiter::hit($rateLimitKey, $timeout);

            $field = $code === '' && $recoveryCode !== '' ? 'recovery_code' : 'code';

            return back()->withErrors([
                $field => $twoFactor->text($field === 'code' ? 'invalid_code' : 'invalid_recovery_code'),
            ]);
        }

        RateLimiter::clear($rateLimitKey);

        $remember = (bool) ($request->session()->get(TwoFactor::PENDING_LOGIN)['remember'] ?? false);
        $request->session()->forget(TwoFactor::PENDING_LOGIN);

        $this->guard()->login($user, $remember);

        $response = $this->sendLoginResponse($request);

        if ($usedRecoveryCode) {
            admin_toastr($twoFactor->text('recovery_used', ['count' => $twoFactor->recoveryCodesLeft($user)]), 'warning');
        }

        return $response;
    }

    /**
     * The user whose password was checked and who still has to type a code.
     *
     * @return Model|null
     */
    protected function pendingTwoFactorUser(Request $request)
    {
        $pending = $request->session()->get(TwoFactor::PENDING_LOGIN);

        if (! is_array($pending) || ($pending['expires'] ?? 0) < time() || ! method_exists($this->guard(), 'getProvider')) {
            $request->session()->forget(TwoFactor::PENDING_LOGIN);

            return null;
        }

        $user = $this->guard()->getProvider()->retrieveById($pending['id'] ?? null);

        return Admin::twoFactor()->enabledFor($user) ? $user : null;
    }

    /**
     * Get a validator for an incoming login request.
     *
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function loginValidator(array $data)
    {
        return Validator::make($data, [
            $this->username() => 'required',
            'password' => 'required',
        ]);
    }

    /**
     * User logout.
     *
     * @return Redirect
     */
    public function getLogout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect(config('admin.route.prefix'));
    }

    /**
     * User setting page.
     *
     *
     * @return Content
     */
    public function getSetting(Content $content)
    {
        $form = $this->settingForm();
        $form->tools(
            function (Form\Tools $tools) {
                $tools->disableList();
                $tools->disableDelete();
                $tools->disableView();
            }
        );

        return $content
            ->title(trans('admin.user_setting'))
            ->body($form->edit(Admin::user()->id));
    }

    /**
     * Update user setting.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putSetting()
    {
        return $this->settingForm()->update(Admin::user()->id);
    }

    /**
     * Model-form for user setting.
     *
     * @return Form
     */
    protected function settingForm()
    {
        $class = config('admin.database.users_model');

        $form = new Form(new $class);

        $form->display('username', trans('admin.username'));
        $form->text('name', trans('admin.name'))->rules('required');
        $form->image('avatar', trans('admin.avatar'));
        $form->password('password', trans('admin.password'))->rules('confirmed|required');
        $form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required')
            ->default(function ($form) {
                return $form->model()->password;
            });

        $form->setAction(admin_url('auth/setting'));

        $form->ignore(['password_confirmation']);

        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = Hash::make($form->password);
            }
        });

        $form->saved(function () {
            admin_toastr(trans('admin.update_succeeded'));

            return redirect(admin_url('auth/setting'));
        });

        return $form;
    }

    /**
     * @return string|TranslatorInterface
     */
    protected function getFailedLoginMessage()
    {
        return Lang::has('auth.failed')
            ? trans('auth.failed')
            : 'These credentials do not match our records.';
    }

    /**
     * Get the post login redirect path.
     *
     * @return string
     */
    protected function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : config('admin.route.prefix');
    }

    /**
     * Send the response after the user was authenticated.
     *
     *
     * @return Response
     */
    protected function sendLoginResponse(Request $request)
    {
        admin_toastr(trans('admin.login_successful'));

        $request->session()->regenerate();

        return redirect()->intended($this->redirectPath());
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    protected function username()
    {
        return 'username';
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return StatefulGuard
     */
    protected function guard()
    {
        return Admin::guard();
    }
}
