<?php

use SuperAdmin\Admin\Auth\Database\Administrator;

class PermissionsFeatureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Administrator::first(), 'admin');
    }

    public function test_permissions_page_loads()
    {
        $this->visit('admin/auth/permissions')
            ->see('Permissions')
            ->assertResponseStatus(200);
    }
}
