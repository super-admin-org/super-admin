<?php

use SuperAdmin\Admin\Auth\Database\Administrator;

class IndexFeatureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Administrator::first(), 'admin');
    }

    public function test_dashboard_accessible()
    {
        $this->visit('admin/')
            ->see('Dashboard')
            ->assertResponseStatus(200);
    }
}
