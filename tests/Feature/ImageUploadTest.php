<?php

use SuperAdmin\Admin\Auth\Database\Administrator;

class ImageUploadFeatureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Administrator::first(), 'admin');
    }

    public function test_image_upload_form_loads()
    {
        $this->visit('admin/images/create')
            ->see('Images')
            ->assertResponseStatus(200);
    }
}
