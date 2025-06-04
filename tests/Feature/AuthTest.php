<?php

class AuthTest extends TestCase
{
    public function test_login_page_displays_form()
    {
        $this->visit('admin/auth/login')
            ->see('login')
            ->see('username')
            ->see('password');
    }

    public function test_login_with_invalid_credentials_shows_error()
    {
        $credentials = ['username' => 'wrong', 'password' => 'incorrect'];

        $this->visit('admin/auth/login')
            ->see('login')
            ->submitForm('Login', $credentials)
            ->seePageIs('admin/auth/login')
            ->see('These credentials do not match our records')
            ->dontSeeIsAuthenticated('admin');
    }

    public function test_login_with_valid_credentials_redirects_to_dashboard()
    {
        $credentials = ['username' => 'admin', 'password' => 'admin'];

        $this->visit('admin/auth/login')
            ->see('login')
            ->submitForm('Login', $credentials)
            ->seePageIs('admin')
            ->seeIsAuthenticated('admin')
            ->see('dashboard');
    }
}
