<?php

use SuperAdmin\Admin\Auth\Database\Administrator;

class UserSettingFeatureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Administrator::first(), 'admin');
    }

    public function test_setting_page_loads()
    {
        $this->visit('admin/auth/setting')
            ->see('User setting')
            ->assertResponseStatus(200);
    }
}
