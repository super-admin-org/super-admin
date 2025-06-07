<?php

use SuperAdmin\Admin\Auth\Database\Administrator;

class UserGridFeatureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Administrator::first(), 'admin');
    }

    public function test_users_index_page_loads()
    {
        $this->visit('admin/users')
            ->see('Users')
            ->assertResponseStatus(200);
    }
}
