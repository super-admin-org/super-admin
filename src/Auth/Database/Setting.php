<?php

namespace SuperAdmin\Admin\Auth\Database;

use Illuminate\Database\Eloquent\Model;

/**
 * Key/value settings changed from the admin panel, such as the active template.
 */
class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Create a new Eloquent model instance.
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('admin.database.settings_table', 'admin_settings'));

        parent::__construct($attributes);
    }
}
