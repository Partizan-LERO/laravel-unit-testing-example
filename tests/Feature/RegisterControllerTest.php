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
        $response = $this->json('POST', route('register'),
            [
                'name' => 'fakeUser',
                'email' => 'fakeUser@mail.com',
                'password' => 'password',
                'password_confirmation' => 'password'
            ]);
        $response->assertStatus(302);
        $response->assertRedirect('/');


        $user = User::where('email','fakeUser@mail.com')->first();
        User::destroy($user->id);
    }

    public function testFailRegister()
    {
        $response = $this->json('POST', route('register'),
            [
                'name' => 'fakeUser',
                'email' => '',
                'password' => 'password',
                'password_confirmation' => 'password'
            ]);
        $response->assertStatus(422);

        $response = $this->json('POST', route('register'),
            [
                'name' => 'fakeUser',
                'email' => 'fakeUser@mail.com',
                'password' => '',
                'password_confirmation' => 'password'
            ]);
        $response->assertStatus(422);

        $response = $this->json('POST', route('register'),
            [
                'name' => 'fakeUser',
                'email' => 'fakeUser@mail.com',
                'password' => 'password_edited',
                'password_confirmation' => 'password'
            ]);
        $response->assertStatus(422);
    }
}
