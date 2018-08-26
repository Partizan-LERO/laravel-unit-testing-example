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
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')->json('POST', '/api/items', ['name' => '123456789test', 'key' => '122313']);

        $response->assertStatus(201);

        $response = json_decode($response->content(), 1);

        $response = $response['data'];

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('key', $response);
        $this->assertArrayHasKey('created_at', $response);
        $this->assertArrayHasKey('updated_at', $response);


        $this->assertEquals($response['name'],'123456789test');
        $this->assertEquals($response['key'], '122313');

        User::destroy($user->id);
    }

    public function testFailKeyStoreItem()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('POST', '/api/items', ['name' => '123456789test', 'key' => str_random(26)]);
        $response->assertStatus(400);

        $response = $this->actingAs($user, 'api')->json('POST', '/api/items', ['name' => '123456789test', 'key' => '']);
        $response->assertStatus(400);

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
        $user = factory(User::class)->create();

        $item = Item::latest()->first()->id;
        $response = $this->actingAs($user, 'api')->json('POST', '/api/items/'  . $item, ['_method'=> 'PATCH', 'name' => 'edited_name', 'key' => 'edited_key']);
        $response->assertStatus(200);

        $response = json_decode($response->content(), 1);

        $response = $response['data'];

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('key', $response);
        $this->assertArrayHasKey('created_at', $response);
        $this->assertArrayHasKey('updated_at', $response);

        $this->assertEquals($response['id'],$item);
        $this->assertEquals($response['name'],'edited_name');
        $this->assertEquals($response['key'], 'edited_key');

        User::destroy($user->id);
    }

    public function testFailKeyUpdateItem()
    {
        $user = factory(User::class)->create();

        $item = Item::first()->id;

        $response = $this->actingAs($user, 'api')->json('POST', '/api/items/'  . $item, ['_method'=> 'PATCH', 'name' => 'edited_name', 'key' => str_random(26)]);
        $response->assertStatus(400);

        $response = $this->actingAs($user, 'api')->json('POST', '/api/items/'  . $item, ['_method'=> 'PATCH', 'name' => 'edited_name', 'key' => '']);
        $response->assertStatus(400);

        $keys = ['key'];
        $response->assertJsonStructure($keys);

        User::destroy($user->id);
    }

    public function testFailNameUpdateItem()
    {
        $user = factory(User::class)->create();

        $item = Item::first()->id;

        $response = $this->actingAs($user, 'api')->json('POST', '/api/items/'  . $item, ['_method'=> 'PATCH', 'name' => str_random(256), 'key' => str_random(24)]);
        $response->assertStatus(400);

        $keys = ['name'];
        $response->assertJsonStructure($keys);

        User::destroy($user->id);
    }

    public function testFailKeyAndNameUpdateItem()
    {
        $user = factory(User::class)->create();

        $item = Item::first()->id;

        $response = $this->actingAs($user, 'api')->json('POST', '/api/items/'  . $item, ['_method'=> 'PATCH', 'name' => str_random(256), 'key' => str_random(26)]);
        $response->assertStatus(400);

        $keys = ['key', 'name'];
        $response->assertJsonStructure($keys);

        User::destroy($user->id);
    }

    public function testDeleteItem()
    {
        $user = factory(User::class)->create();

        $item = Item::first()->id;

        $this->actingAs($user, 'api')->json('DELETE','/api/items/' . $item)->assertStatus(204);

        User::destroy($user->id);
    }
}
