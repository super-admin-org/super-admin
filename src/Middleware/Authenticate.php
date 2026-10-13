<?php

namespace SuperAdmin\Admin\Middleware;

use Closure;
use Illuminate\Http\Request;
use SuperAdmin\Admin\Facades\Admin;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        \config(['auth.defaults.guard' => 'admin']);

        $redirectTo = admin_base_path(config('admin.auth.redirect_to', 'auth/login'));

        if (Admin::guard()->guest() && ! $this->shouldPassThrough($request)) {
            return redirect()->to($redirectTo);
        }

        return $next($request);
    }

    /**
     * Determine if the request has a URI that should pass through verification.
     *
     * @param  Request  $request
     * @return bool
     */
    protected function shouldPassThrough($request)
    {
        // The following routes do not authenticate the login. The login page
        // itself must always pass, otherwise guests are redirected in a loop.
        $excepts = config('admin.auth.excepts', []);
        $excepts[] = config('admin.auth.redirect_to', 'auth/login');

        array_delete($excepts, [
            '_handle_action_',
            '_handle_form_',
            '_handle_selectable_',
            '_handle_renderable_',
        ]);

        return collect($excepts)
            ->map('admin_base_path')
            ->contains(function ($except) use ($request) {
                if ($except !== '/') {
                    $except = trim($except, '/');
                }

                return $request->is($except);
            });
    }
}
