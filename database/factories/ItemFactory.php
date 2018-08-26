<?php

use App\Item;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'name' => str_random(10),
        'key' => str_random(25)
    ];
});
