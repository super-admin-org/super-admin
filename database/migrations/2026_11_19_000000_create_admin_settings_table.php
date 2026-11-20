<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminSettingsTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function getConnection()
    {
        return config('admin.database.connection') ?: config('database.default');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = config('admin.database.settings_table', 'admin_settings');

        if (Schema::connection($this->getConnection())->hasTable($table)) {
            return;
        }

        Schema::connection($this->getConnection())->create($table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('key', 190)->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection($this->getConnection())->dropIfExists(config('admin.database.settings_table', 'admin_settings'));
    }
}
