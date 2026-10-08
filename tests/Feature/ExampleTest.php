<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_returns_successful_response(): void
    {
        $this->seed();
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('CaterHub');
    }

    public function test_search_page_returns_successful_response(): void
    {
        $this->seed();
        $response = $this->get('/search');

        $response->assertStatus(200);
        $response->assertSee('Cari Vendor');
    }

    public function test_login_page_returns_successful_response(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Masuk ke CaterHub');
    }
}
