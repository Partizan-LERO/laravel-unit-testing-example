<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginControllerTest extends TestCase
{
    public function testLogout()
    {
       $response = $this->json('GET', route('logout'));
       $response->assertStatus(302);
       $response->assertRedirect('/');
    }
}
