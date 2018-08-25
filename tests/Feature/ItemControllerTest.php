<?php

namespace Tests\Feature;

use App\Item;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->json('GET', '/');

        $response->assertStatus(200);

        $response->assertViewHas('items');
    }

    public function testShow()
    {
        $id = Item::first()->id;
        $response = $this->json('GET', 'item/show/' . $id);

        $response->assertStatus(200);

        $response->assertViewHas('item');
    }

    public function testCreate()
    {
        $response = $this->json('GET', 'item/create');

        $response->assertStatus(200);
    }

    public function testEdit()
    {
        $id = Item::first()->id;
        $response = $this->json('GET', route('edit-item', $id));

        $response->assertStatus(200);

        $response->assertViewHas('item');
    }

    public function testUpdate()
    {
        $id = Item::first()->id;

        $response = $this->json('POST', route('update-item', $id), ['_method'=> 'PATCH', 'name' => 'edited_name', 'key' => 'edited_key']);

        $response->assertStatus(302);

        $response->assertRedirect(route('index-item'));
    }

    public function testFailKeyUpdate()
    {
        $id = Item::first()->id;

        $response = $this->json('POST', route('update-item', $id),
            ['_method'=> 'PATCH', 'name' => 'edited_name', 'key' => randomString(26)]);

        $keys = ['key'];
        $response->assertJsonValidationErrors($keys);

        $response->assertStatus(422);
    }

    public function testEmptyKeyUpdate()
    {
        $id = Item::first()->id;

        $response = $this->json('POST', route('update-item', $id),
            ['_method'=> 'PATCH', 'name' => 'edited_name', 'key' => '']);

        $keys = ['key'];
        $response->assertJsonValidationErrors($keys);

        $response->assertStatus(422);
    }

    public function testFailNameUpdate()
    {
        $id = Item::first()->id;

        $response = $this->json('POST', route('update-item', $id),
            ['_method'=> 'PATCH', 'name' => randomString(256), 'key' => randomString(21)]);

        $keys = ['name'];
        $response->assertJsonValidationErrors($keys);

        $response->assertStatus(422);
    }

    public function testFailNameAndKeyUpdate()
    {
        $id = Item::first()->id;

        $response = $this->json('POST', route('update-item', $id),
            ['_method'=> 'PATCH', 'name' => randomString(256), 'key' => randomString(26)]);

        $keys = ['name', 'key'];
        $response->assertJsonValidationErrors($keys);

        $response->assertStatus(422);
    }

    public function testStore()
    {
        $response = $this->json('POST', route('store-item'), ['name' => 'New Item', 'key' => 'Test secret key']);

        $response->assertStatus(302);

        $response->assertRedirect(route('index-item'));
    }

    public function testFailKeyStore()
    {
        $response = $this->json('POST', route('store-item'), ['name' => 'New Item', 'key' => randomString(26)]);

        $response->assertStatus(422);

        $keys = ['key'];
        $response->assertJsonValidationErrors($keys);
    }

    public function testEmptyKeyStore()
    {
        $response = $this->json('POST', route('store-item'), ['name' => 'New Item', 'key' => '']);

        $response->assertStatus(422);

        $keys = ['key'];
        $response->assertJsonValidationErrors($keys);
    }

    public function testFailNameStore()
    {
        $response = $this->json('POST', route('store-item'), ['name' => randomString(256), 'key' => randomString(24)]);

        $response->assertStatus(422);

        $keys = ['name'];
        $response->assertJsonValidationErrors($keys);
    }

    public function testFailNameAndKeyStore()
    {
        $response = $this->json('POST', route('store-item'), ['name' => randomString(256), 'key' => randomString(26)]);

        $response->assertStatus(422);

        $keys = ['name', 'key'];
        $response->assertJsonValidationErrors($keys);
    }

    public function testDelete()
    {
        $id = Item::first()->id;

        $response = $this->json('DELETE', route('delete-item', $id));

        $response->assertStatus(302);

        $response->assertRedirect(route('index-item'));
    }
}
