<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

$factory->define(App\Product::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'code' => $faker->md5,
        'description' => $faker->paragraph(10),
        'retail_price' => sprintf('%.2f', $faker->randomFloat(2)),
        'purchase_price' => sprintf('%.2f', $faker->randomFloat(2)),
    ];
});
