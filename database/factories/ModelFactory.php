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
        App\User::NAME => $faker->name,
        App\User::EMAIL => $faker->email,
    ];
});

$factory->define(App\Product::class, function (Faker\Generator $faker) {
    return [
        App\Product::NAME => $faker->name,
        App\Product::CODE => $faker->md5,
        App\Product::DESCRIPTION => $faker->paragraph(10),

    ];
});

$factory->define(App\Merchandise::class, function (Faker\Generator $faker) {
    return [
        App\Merchandise::IS_ACTIVE => $faker->boolean,
        App\Merchandise::RETAIL_PRICE => sprintf('%.2f', $faker->randomFloat(2)),
        App\Merchandise::PURCHASE_PRICE => sprintf('%.2f', $faker->randomFloat(2)),
    ];
});

$factory->define(App\Supplier::class, function (Faker\Generator $faker) {
    return [
        App\Supplier::NAME => $faker->name,
        App\Supplier::TRADE_NAME => $faker->company,
        App\Supplier::LEGAL_DOCUMENT_CODE => $faker->md5,
    ];
});

$factory->define(App\Client::class, function (Faker\Generator $faker) {
    return [
        App\Client::NAME => $faker->name,
        App\Client::LEGAL_DOCUMENT_CODE => $faker->md5,
    ];
});

$factory->define(App\Contact::class, function (Faker\Generator $faker) {
    return [
        App\Contact::PHONE => $faker->phoneNumber,
        App\Contact::ADDRESS => $faker->streetAddress,
        App\Contact::ADDRESS_COMPLEMENT => $faker->address,
        App\Contact::POSTAL_CODE => $faker->postcode,
        App\Contact::CITY => $faker->city,
        App\Contact::REGION => $faker->city,
        App\Contact::COUNTRY => $faker->country,
    ];
});

$factory->define(App\Purchase::class, function (Faker\Generator $faker) {
    return [
        App\Purchase::CODE => $faker->md5,
        App\Purchase::DESCRIPTION => $faker->paragraph(5),
        App\Purchase::GROSS_VALUE => sprintf('%.2f', $faker->randomFloat(2)),
        App\Purchase::NET_VALUE => sprintf('%.2f', $faker->randomFloat(2)),
        App\Purchase::DISCOUNT => sprintf('%.2f', $faker->randomFloat(2)),
    ];
});

$factory->define(App\DeliveryAddress::class, function (Faker\Generator $faker) {
    return [
        App\DeliveryAddress::IS_DEFAULT => $faker->boolean,
    ];
});

$factory->define(App\Delivery::class, function (Faker\Generator $faker) {
    $dateTime = $faker->dateTime;
    $deliveryTime = $faker->numberBetween(1, 30);
    return [
        App\Delivery::SENT_AT => $dateTime,
        App\Delivery::ARRIVED_AT => $dateTime->add(new \DateInterval("P{$deliveryTime}D")),
        App\Delivery::DELIVERY_TIME => $deliveryTime,
    ];
});
