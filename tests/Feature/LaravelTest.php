<?php

class LaravelFeatureTest extends TestCase
{
    public function test_homepage_shows_laravel()
    {
        $this->visit('/')
            ->see('Laravel')
            ->assertResponseStatus(200);
    }
}
