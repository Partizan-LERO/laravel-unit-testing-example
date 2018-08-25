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
        $response = $this->json('POST', '/api/items/'  . $item, ['_method'=> 'PATCH', 'name' => 'edited', 'key' => 'edited']);
        $response->assertStatus(200);

        $response = json_decode($response->content(), 1);

        $response = $response['data'];

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('key', $response);
        $this->assertArrayHasKey('created_at', $response);
        $this->assertArrayHasKey('updated_at', $response);

        $this->assertEquals($response['id'],$item);
        $this->assertEquals($response['name'],'edited');
        $this->assertEquals($response['key'], 'edited');
    }

    public function testDeleteItem()
    {
        $item = Item::first()->id;

        $this->delete('/api/items/' . $item)->assertStatus(204);
    }
}
