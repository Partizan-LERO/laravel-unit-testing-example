<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterControllerTest extends TestCase
{
    use WithoutMiddleware;

    public function testRegister()
    {
        $name = str_random(6);
        $password = str_random(6);

        $response = $this->json('POST', route('register'),
            [
                'name' => $name,
                'email' => 'fakeUser@mail.com',
                'password' => $password,
                'password_confirmation' => $password
            ]);
        $response->assertStatus(302);
        $response->assertRedirect('/');

        $this->assertDatabaseHas('users', ['name' => $name]);

        $user = User::where('email','fakeUser@mail.com')->first();
        User::destroy($user->id);
    }

    public function testFailRegister()
    {
        $name = str_random(10);
        $password = str_random(10);

        $response = $this->json('POST', route('register'),
            [
                'name' => $name,
                'email' => '',
                'password' => $password,
                'password_confirmation' => $password
            ]);
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', ['name' => $name]);

        $response = $this->json('POST', route('register'),
            [
                'name' => $name,
                'email' => 'fakeUser@mail.com',
                'password' => '',
                'password_confirmation' => $password
            ]);
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', ['name' => $name]);

        $response = $this->json('POST', route('register'),
            [
                'name' => $name,
                'email' => 'fakeUser@mail.com',
                'password' => $password,
                'password_confirmation' => str_random(11)
            ]);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', ['name' => $name]);
    }
}
