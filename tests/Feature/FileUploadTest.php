<?php

use SuperAdmin\Admin\Auth\Database\Administrator;

class FileUploadFeatureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Administrator::first(), 'admin');
    }

    public function test_page_loads()
    {
        $this->visit('admin/files/create')
            ->see('Files')
            ->assertResponseStatus(200);
    }
}
