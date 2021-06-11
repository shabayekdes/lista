<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    $name = $this->faker->sentence();
    return [
        'name' => $name,
        'slug' => Str::slug($name),
        'category_id' => factory(Category::class)->create()->id,
        'brand_id' => factory(Brand::class)->create()->id,
        'price' => $faker->randomFloat(2)

    ];
});
