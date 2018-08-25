<?php

namespace Tests\Feature;

use App\Http\Resources\ItemResource;
use App\Item;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemTest extends TestCase
{
    public function testStoreItem()
    {
        $response = $this->json('POST', '/api/items', ['name' => '123456789test', 'key' => '122313']);

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
    }

    public function testFailKeyStoreItem()
    {
        $response = $this->json('POST', '/api/items', ['name' => '123456789test', 'key' => randomString(26)]);
        $response->assertStatus(400);

        $response = $this->json('POST', '/api/items', ['name' => '123456789test', 'key' => '']);
        $response->assertStatus(400);
    }

    public function testGetItems()
    {
        $this->get('/api/items')->assertStatus(200);
    }

    public function testGetItem()
    {
        $item = Item::first()->id;

        $this->get('/api/items/' . $item)->assertStatus(200);
    }

    public function testUpdateItem()
    {
        $item = Item::latest()->first()->id;
        $response = $this->json('POST', '/api/items/'  . $item, ['_method'=> 'PATCH', 'name' => 'edited_name', 'key' => 'edited_key']);
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
    }

    public function testFailKeyUpdateItem()
    {
        $item = Item::first()->id;

        $response = $this->json('POST', '/api/items/'  . $item, ['_method'=> 'PATCH', 'name' => 'edited_name', 'key' => randomString(26)]);
        $response->assertStatus(400);

        $response = $this->json('POST', '/api/items/'  . $item, ['_method'=> 'PATCH', 'name' => 'edited_name', 'key' => '']);
        $response->assertStatus(400);

        $keys = ['key'];
        $response->assertJsonStructure($keys);
    }

    public function testFailNameUpdateItem()
    {
        $item = Item::first()->id;

        $response = $this->json('POST', '/api/items/'  . $item, ['_method'=> 'PATCH', 'name' => randomString(256), 'key' => randomString(24)]);
        $response->assertStatus(400);

        $keys = ['name'];
        $response->assertJsonStructure($keys);
    }

    public function testFailKeyAndNameUpdateItem()
    {
        $item = Item::first()->id;

        $response = $this->json('POST', '/api/items/'  . $item, ['_method'=> 'PATCH', 'name' => randomString(256), 'key' => randomString(26)]);
        $response->assertStatus(400);

        $keys = ['key', 'name'];
        $response->assertJsonStructure($keys);
    }

    public function testDeleteItem()
    {
        $item = Item::first()->id;

        $this->delete('/api/items/' . $item)->assertStatus(204);
    }
}
