<?php

use App\Models\Offer;
use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Offer::class, function (Faker $faker) {
    return [
        'product_id' => factory(Product::class)->create()->id,
        'precentges' => 10,
    ];
});
