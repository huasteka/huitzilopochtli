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

$factory->define(App\Supplier::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'trade_name' => $faker->company,
        'legal_document_code' => $faker->md5,
    ];
});

$factory->define(App\Client::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'legal_document_code' => $faker->md5,
    ];
});

$factory->define(App\Contact::class, function (Faker\Generator $faker) {
    return [
        'phone' => $faker->phoneNumber,
        'address' => $faker->streetAddress,
        'address_complement' => $faker->address,
        'postal_code' => $faker->postcode,
        'city' => $faker->city,
        'region' => $faker->city,
        'country' => $faker->country,
    ];
});
