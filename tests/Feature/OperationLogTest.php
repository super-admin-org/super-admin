<?php

use SuperAdmin\Admin\Auth\Database\Administrator;

class OperationLogFeatureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Administrator::first(), 'admin');
    }

    public function test_logs_page_loads()
    {
        $this->visit('admin/auth/logs')
            ->see('Operation log')
            ->assertResponseStatus(200);
    }
}
