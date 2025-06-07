<?php

use SuperAdmin\Admin\Auth\Database\Administrator;

class RolesFeatureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Administrator::first(), 'admin');
    }

    public function test_roles_page_loads()
    {
        $this->visit('admin/auth/roles')
            ->see('Roles')
            ->assertResponseStatus(200);
    }
}
