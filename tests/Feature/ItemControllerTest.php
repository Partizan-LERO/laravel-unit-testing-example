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

        $name = str_random(10);
        $key  = str_random(10);

        $id = factory(Item::class)->create()->id;

        $response = $this->json('POST', route('update-item', $id), ['_method'=> 'PATCH', 'name' => $name, 'key' => $key]);

        $response->assertStatus(302);

        $response->assertRedirect(route('index-item'));

        $this->assertDatabaseHas('items', [
            'name' => $name,
            'key' => $key
        ]);

        Item::destroy($id);
    }

    public function testFailKeyUpdate()
    {
        $name = str_random(10);
        $key = str_random(26);

        $this->loginWithFakeUser();

        $id = factory(Item::class)->create()->id;
        $keys = ['key'];

        $response = $this->json('POST', route('update-item', $id),
            ['_method'=> 'PATCH', 'name' => $name, 'key' => $key]);

        $this->assertDatabaseMissing('items', ['name' => $name, 'key' => $key]);

        $response->assertJsonValidationErrors($keys);
        $response->assertStatus(422);

        $response = $this->json('POST', route('update-item', $id),
            ['_method'=> 'PATCH', 'name' => $name, 'key' => '']);

        $this->assertDatabaseMissing('items', ['name' => $name, 'key' => '']);

        $response->assertJsonValidationErrors($keys);
        $response->assertStatus(422);

        Item::destroy($id);
    }

    public function testFailNameUpdate()
    {
        $name = str_random(256);
        $key = str_random(24);

        $this->loginWithFakeUser();

        $id = factory(Item::class)->create()->id;

        $response = $this->json('POST', route('update-item', $id),
            ['_method'=> 'PATCH', 'name' => $name, 'key' => $key]);

        $this->assertDatabaseMissing('items', ['name' => $name, 'key' => $key]);

        $keys = ['name'];
        $response->assertJsonValidationErrors($keys);

        $response->assertStatus(422);

        Item::destroy($id);
    }

    public function testFailNameAndKeyUpdate()
    {
        $name = str_random(256);
        $key = str_random(26);

        $this->loginWithFakeUser();

        $id = factory(Item::class)->create()->id;

        $response = $this->json('POST', route('update-item', $id),
            ['_method'=> 'PATCH', 'name' => $name, 'key' => $key]);

        $keys = ['name', 'key'];
        $response->assertJsonValidationErrors($keys);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('items', ['name' => $name, 'key' => $key]);

        Item::destroy($id);
    }

    public function testStore()
    {
        $this->loginWithFakeUser();

        $name = str_random(10);
        $key  = str_random(10);

        $response = $this->json('POST', route('store-item'), [
            'name' => $name,
            'key' => $key
        ]);

        $this->assertDatabaseHas('items', [
            'name' => $name,
            'key' => $key
        ]);

        $response->assertStatus(302);

        $response->assertRedirect(route('index-item'));

        $item = Item::where('name', $name)->first();

        Item::destroy($item->id);
    }

    public function testFailKeyStore()
    {
        $name = str_random(10);
        $key = str_random(26);

        $this->loginWithFakeUser();

        $response = $this->json('POST', route('store-item'), ['name' => $name, 'key' => $key]);

        $this->assertDatabaseMissing('items', ['name' => $name, 'key' => $key]);

        $response->assertStatus(422);

        $keys = ['key'];
        $response->assertJsonValidationErrors($keys);

        $response = $this->json('POST', route('store-item'), ['name' => $name, 'key' => '']);
        $response->assertStatus(422);

        $this->assertDatabaseMissing('items', ['name' => $name, 'key' => '']);

        $keys = ['key'];
        $response->assertJsonValidationErrors($keys);
    }

    public function testFailNameStore()
    {
        $name = str_random(256);
        $key = str_random(24);

        $this->loginWithFakeUser();

        $response = $this->json('POST', route('store-item'), ['name' => $name, 'key' => $key]);

        $response->assertStatus(422);

        $keys = ['name'];

        $response->assertJsonValidationErrors($keys);

        $this->assertDatabaseMissing('items', ['name' => $name, 'key' => $key]);
    }

    public function testFailNameAndKeyStore()
    {
        $name = str_random(256);
        $key = str_random(26);

        $this->loginWithFakeUser();

        $response = $this->json('POST', route('store-item'), ['name' => $name, 'key' => $key]);

        $response->assertStatus(422);

        $keys = ['name', 'key'];

        $response->assertJsonValidationErrors($keys);

        $this->assertDatabaseMissing('items', ['name' => $name, 'key' => $key]);
    }

    public function testDelete()
    {
        $this->loginWithFakeUser();

        $id = factory(Item::class)->create()->id;

        $response = $this->json('DELETE', route('delete-item', $id));

        $response->assertStatus(302);

        $response->assertRedirect(route('index-item'));

        $this->assertDatabaseMissing('items', ['id' => $id]);
    }
}
