<?php

use SuperAdmin\Admin\Auth\Database\Administrator;

class MenuFeatureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Administrator::first(), 'admin');
    }

    public function test_menu_page_loads()
    {
        $this->visit('admin/auth/menu')
            ->see('Menu')
            ->assertResponseStatus(200);
    }
}
