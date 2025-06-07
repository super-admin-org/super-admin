<?php

use SuperAdmin\Admin\Auth\Database\Administrator;

class UserFormFeatureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Administrator::first(), 'admin');
    }

    public function test_user_create_form_loads()
    {
        $this->visit('admin/users/create')
            ->see('Create')
            ->assertResponseStatus(200);
    }
}
