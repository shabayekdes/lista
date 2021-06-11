<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    $name = $this->faker->sentence();
    return [
        'name' => $name,
        'slug' => Str::slug($name)
    ];
});
