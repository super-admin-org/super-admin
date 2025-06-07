<?php

class InstallFeatureTest extends TestCase
{
    public function test_admin_directory_exists()
    {
        $this->assertFileExists(admin_path());
    }
}
