<?php

use SuperAdmin\Admin\Auth\Database\Administrator;

class UsersFeatureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Administrator::first(), 'admin');
    }

    public function test_users_page_loads()
    {
        $this->visit('admin/auth/users')
            ->see('Users')
            ->assertResponseStatus(200);
    }
}
