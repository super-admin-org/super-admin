<?php

namespace SuperAdmin\Admin\Auth\TwoFactor;

use Illuminate\Database\Eloquent\Model;
use SuperAdmin\Admin\Actions\Response;
use SuperAdmin\Admin\Actions\RowAction;
use SuperAdmin\Admin\Facades\Admin;

/**
 * Admin > Users row action: turn two-factor off for a user who lost their phone.
 */
class ResetTwoFactor extends RowAction
{
    public $icon = 'icon-unlock';

    public function name()
    {
        return Admin::twoFactor()->text('reset');
    }

    public function dialog()
    {
        $this->confirm(Admin::twoFactor()->text('reset').'?', Admin::twoFactor()->text('reset_confirm'));
    }

    /**
     * Only users who may edit admin users can reset someone's two-factor.
     */
    public function authorize($user, $model): bool
    {
        return Admin::twoFactor()->userCan('PUT', 'auth/users/'.($model instanceof Model ? $model->getKey() : ''));
    }

    public function handle(Model $model): Response
    {
        Admin::twoFactor()->disable($model);

        return $this->response()
            ->success(Admin::twoFactor()->text('reset_done', ['name' => $model->getAttribute('username')]))
            ->refresh();
    }
}
