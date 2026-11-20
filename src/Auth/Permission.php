<?php

namespace SuperAdmin\Admin\Auth;

use Illuminate\Http\Exceptions\HttpResponseException;
use SuperAdmin\Admin\Facades\Admin;

class Permission
{
    /**
     * Check permission.
     *
     *
     * @return true
     */
    public static function check($permission)
    {
        if (static::isAdministrator()) {
            return true;
        }

        if (is_array($permission)) {
            collect($permission)->each(function ($permission) {
                call_user_func([self::class, 'check'], $permission);
            });

            return;
        }

        if (Admin::user()->cannot($permission)) {
            static::error();
        }
    }

    /**
     * Roles allowed to access.
     *
     *
     * @return true
     */
    public static function allow($roles)
    {
        if (static::isAdministrator()) {
            return true;
        }

        if (! Admin::user()->inRoles($roles)) {
            static::error();
        }
    }

    /**
     * Don't check permission.
     *
     * @return bool
     */
    public static function free()
    {
        return true;
    }

    /**
     * Roles denied to access.
     *
     *
     * @return true
     */
    public static function deny($roles)
    {
        if (static::isAdministrator()) {
            return true;
        }

        if (Admin::user()->inRoles($roles)) {
            static::error();
        }
    }

    /**
     * Send error response page.
     *
     * The response is raised as an exception so the framework returns it
     * through the normal middleware stack (including the PJAX filter)
     * instead of sending it and terminating the process.
     */
    public static function error()
    {
        $response = response(Admin::content()->withError(trans('admin.deny')));

        if (! request()->pjax() && request()->ajax()) {
            abort(403, trans('admin.deny'));
        }

        throw new HttpResponseException($response);
    }

    /**
     * If current user is administrator.
     *
     * @return mixed
     */
    public static function isAdministrator()
    {
        return Admin::user()->isRole('administrator');
    }
}
