<?php

namespace Tests\Feature;

use App\Item;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;


class ItemControllerTest extends TestCase
{
    use WithoutMiddleware;

    public function loginWithFakeUser()
    {
        $user = new User();

        $user->id = 1;
        $user->api_token = 'test_your_super_secret_api_token';

        $this->be($user);
    }

    public function testIndex()
    {
        $this->loginWithFakeUser();

        $response = $this->json('GET', route('index-item'));

        $response->assertStatus(200);

        $response->assertViewHas('items');
    }

    public function testShow()
    {
        $this->loginWithFakeUser();

        $id = factory(Item::class)->create()->id;

        $response = $this->json('GET', route('show-item', $id));

        $response->assertStatus(200);

        $response->assertViewHas('item');

        Item::destroy($id);
    }

    public function testCreate()
    {
        $this->loginWithFakeUser();

        $response = $this->json('GET', route('create-item'));

        $response->assertStatus(200);
    }

    public function testEdit()
    {
        $this->loginWithFakeUser();

        $id = factory(Item::class)->create()->id;
        $response = $this->json('GET', route('edit-item', $id));
        Item::destroy($id);

        $response->assertStatus(200);

        $response->assertViewHas('item');
    }

    public function testUpdate()
    {
        $this->loginWithFakeUser();

        $id = factory(Item::class)->create()->id;

        $response = $this->json('POST', route('update-item', $id), ['_method'=> 'PATCH', 'name' => 'edited_name', 'key' => 'edited_key']);

        $response->assertStatus(302);

        $response->assertRedirect(route('index-item'));

        Item::destroy($id);
    }

    public function testFailKeyUpdate()
    {
        $this->loginWithFakeUser();

        $id = factory(Item::class)->create()->id;
        $keys = ['key'];

        $response = $this->json('POST', route('update-item', $id),
            ['_method'=> 'PATCH', 'name' => 'edited_name', 'key' => str_random(26)]);



        $response->assertJsonValidationErrors($keys);
        $response->assertStatus(422);

        $response = $this->json('POST', route('update-item', $id),
            ['_method'=> 'PATCH', 'name' => 'edited_name', 'key' => '']);

        $response->assertJsonValidationErrors($keys);
        $response->assertStatus(422);

        Item::destroy($id);
    }

    public function testFailNameUpdate()
    {
        $this->loginWithFakeUser();

        $id = factory(Item::class)->create()->id;

        $response = $this->json('POST', route('update-item', $id),
            ['_method'=> 'PATCH', 'name' => str_random(256), 'key' => str_random(21)]);

        $keys = ['name'];
        $response->assertJsonValidationErrors($keys);

        $response->assertStatus(422);

        Item::destroy($id);
    }

    public function testFailNameAndKeyUpdate()
    {
        $this->loginWithFakeUser();

        $id = factory(Item::class)->create()->id;

        $response = $this->json('POST', route('update-item', $id),
            ['_method'=> 'PATCH', 'name' => str_random(256), 'key' => str_random(26)]);

        $keys = ['name', 'key'];
        $response->assertJsonValidationErrors($keys);

        $response->assertStatus(422);

        Item::destroy($id);
    }

    public function testStore()
    {
        $this->loginWithFakeUser();

        $response = $this->json('POST', route('store-item'), [
            'name' => 'New Item',
            'key' => 'Test secret key'
        ]);

        $response->assertStatus(302);

        $response->assertRedirect(route('index-item'));

        $item = Item::where('name', 'New Item')->first();
        Item::destroy($item->id);
    }

    public function testFailKeyStore()
    {
        $this->loginWithFakeUser();

        $response = $this->json('POST', route('store-item'), ['name' => 'New Item', 'key' => str_random(26)]);

        $response->assertStatus(422);

        $keys = ['key'];
        $response->assertJsonValidationErrors($keys);

        $response = $this->json('POST', route('store-item'), ['name' => 'New Item', 'key' => '']);
        $response->assertStatus(422);

        $keys = ['key'];
        $response->assertJsonValidationErrors($keys);
    }

    public function testFailNameStore()
    {
        $this->loginWithFakeUser();

        $response = $this->json('POST', route('store-item'), ['name' => str_random(256), 'key' => str_random(24)]);

        $response->assertStatus(422);

        $keys = ['name'];
        $response->assertJsonValidationErrors($keys);
    }

    public function testFailNameAndKeyStore()
    {
        $this->loginWithFakeUser();

        $response = $this->json('POST', route('store-item'), ['name' => str_random(256), 'key' => str_random(26)]);

        $response->assertStatus(422);

        $keys = ['name', 'key'];
        $response->assertJsonValidationErrors($keys);
    }

    public function testDelete()
    {
        $this->loginWithFakeUser();

        $id = factory(Item::class)->create()->id;

        $response = $this->json('DELETE', route('delete-item', $id));

        $response->assertStatus(302);

        $response->assertRedirect(route('index-item'));
    }
}
