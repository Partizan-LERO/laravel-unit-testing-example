<?php

namespace Tests\Unit;

use App\Item;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemModelTest extends TestCase
{

    public function testGetLabelModel()
    {
        $item = new Item();
        $this->assertEquals('item', $item->getModelLabel());
    }
}
