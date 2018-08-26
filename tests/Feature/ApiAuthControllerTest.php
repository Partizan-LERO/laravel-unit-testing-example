<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiAuthControllerTest extends TestCase
{
    public function testFailLogin()
    {
        $user = User::create([
            'name' => "test",
            'email' => "test@test.com",
            'password' => bcrypt('password'),
        ]);

        $response = $this->json('POST', '/api/login', ['email' => $user->email]);
        $response->assertStatus(400);
        $response->assertJsonStructure(['password']);

        $response = $this->json('POST', '/api/login', ['password' => 'password']);
        $response->assertStatus(400);
        $response->assertJsonStructure(['email']);

        $response = $this->json('POST', '/api/login', ['email' => $user->email, 'password' => 'just wrong password']);
        $response->assertStatus(201);

        $response = $this->json('POST', '/api/login', ['email' => 'wrong-email-format', 'password' => 'password']);
        $response->assertStatus(400);

        User::destroy($user->id);
    }

    public function testSuccessLogin()
    {
        $user = User::create([
            'name' => "test",
            'email' => "test@test.com",
            'password' => bcrypt('password'),
        ]);

        $response = $this->json('POST', '/api/login', ['email' => $user->email, 'password' => 'password']);
        $this->assertTrue(Auth::check());

        $response->assertStatus(200);
        $response->assertJson(['api_token' => $user->api_token]);

        User::destroy($user->id);
    }
}
