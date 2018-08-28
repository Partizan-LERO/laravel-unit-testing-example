<?php

namespace Tests\Feature;

use App\Http\Resources\ItemResource;
use App\Item;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiItemControllerTest extends TestCase
{
    public function testStoreItem()
    {
        $name = str_random(10);
        $key  = str_random(10);

        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')->json('POST', '/api/items', ['name' => $name, 'key' => $key]);

        $this->assertDatabaseHas('items', ['name' => $name, 'key' => $key]);

        $response->assertStatus(201);

        $response = json_decode($response->content(), 1);

        $response = $response['data'];

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('key', $response);
        $this->assertArrayHasKey('created_at', $response);
        $this->assertArrayHasKey('updated_at', $response);


        $this->assertEquals($response['name'], $name);
        $this->assertEquals($response['key'],  $key);

        User::destroy($user->id);
    }

    public function testFailKeyStoreItem()
    {
        $name = str_random(10);

        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('POST', '/api/items', ['name' => $name, 'key' => str_random(26)]);
        $response->assertStatus(400);

        $this->assertDatabaseMissing('items', ['name' => $name]);

        $response = $this->actingAs($user, 'api')->json('POST', '/api/items', ['name' => $name, 'key' => '']);
        $response->assertStatus(400);

        $this->assertDatabaseMissing('items', ['name' => $name]);

        User::destroy($user->id);
    }

    public function testGetItems()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')->json('GET','/api/items')->assertStatus(200);

        User::destroy($user->id);
    }

    public function testGetItem()
    {
        $user = factory(User::class)->create();

        $item = Item::first()->id;

        $this->actingAs($user, 'api')->json('GET', '/api/items/' . $item)->assertStatus(200);

        User::destroy($user->id);
    }

    public function testUpdateItem()
    {
        $name = str_random(10);
        $key  = str_random(10);

        $user = factory(User::class)->create();

        $item = Item::latest()->first()->id;
        $response = $this->actingAs($user, 'api')->json('POST', '/api/items/'  . $item,
            ['_method'=> 'PATCH', 'name' => $name, 'key' => $key]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('items', ['name' => $name, 'key' => $key]);

        $response = json_decode($response->content(), 1);

        $response = $response['data'];

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('key', $response);
        $this->assertArrayHasKey('created_at', $response);
        $this->assertArrayHasKey('updated_at', $response);

        $this->assertEquals($response['id'],$item);
        $this->assertEquals($response['name'], $name);
        $this->assertEquals($response['key'], $key);

        User::destroy($user->id);
    }

    public function testFailKeyUpdateItem()
    {
        $name = str_random(10);

        $user = factory(User::class)->create();

        $item = Item::first()->id;

        $response = $this->actingAs($user, 'api')->json('POST', '/api/items/'  . $item,
            ['_method'=> 'PATCH', 'name' => $name, 'key' => str_random(26)]);
        $response->assertStatus(400);
        $this->assertDatabaseMissing('items', ['name' => $name]);

        $response = $this->actingAs($user, 'api')->json('POST', '/api/items/'  . $item,
            ['_method'=> 'PATCH', 'name' => $name, 'key' => '']);
        $response->assertStatus(400);
        $this->assertDatabaseMissing('items', ['name' => $name]);

        $keys = ['key'];
        $response->assertJsonStructure($keys);

        User::destroy($user->id);
    }

    public function testFailNameUpdateItem()
    {
        $name = str_random(256);

        $user = factory(User::class)->create();

        $item = Item::first()->id;

        $response = $this->actingAs($user, 'api')->json('POST', '/api/items/'  . $item,
            ['_method'=> 'PATCH', 'name' => $name, 'key' => str_random(24)]);
        $response->assertStatus(400);

        $this->assertDatabaseMissing('items', ['name' => $name]);

        $keys = ['name'];

        $response->assertJsonStructure($keys);

        User::destroy($user->id);
    }

    public function testFailKeyAndNameUpdateItem()
    {
        $name = str_random(256);
        $key = str_random(26);

        $user = factory(User::class)->create();

        $item = Item::first()->id;

        $response = $this->actingAs($user, 'api')->json('POST', '/api/items/'  . $item,
            ['_method'=> 'PATCH', 'name' => $name, 'key' => $key]);
        $response->assertStatus(400);

        $this->assertDatabaseMissing('items', ['name' => $name, 'key' => $key]);

        $keys = ['key', 'name'];
        $response->assertJsonStructure($keys);

        User::destroy($user->id);
    }

    public function testDeleteItem()
    {
        $user = factory(User::class)->create();

        $id = Item::first()->id;

        $this->actingAs($user, 'api')->json('DELETE','/api/items/' . $id)->assertStatus(204);

        $this->assertDatabaseMissing('items', ['id' => $id]);
    }
}
