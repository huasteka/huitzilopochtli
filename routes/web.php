<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->group(['prefix' => 'api'], function () use ($app) {
    $createRestResource = function (Laravel\Lumen\Application $app, $controller, $resource_name, $resource_id) {
        $app->get("/{$resource_name}", [
            'as' => "{$resource_name}.index",
            'uses' => "{$controller}@index",
        ]);
        $app->post("/{$resource_name}", [
            'as' => "{$resource_name}.store",
            'uses' => "{$controller}@store",
        ]);
        $app->get("/{$resource_name}/{{$resource_id}}", [
            'as' => "{$resource_name}.show",
            'uses' => "{$controller}@show",
        ]);
        $app->put("/{$resource_name}/{{$resource_id}}", [
            'as' => "{$resource_name}.update",
            'uses' => "{$controller}@update",
        ]);
        $app->delete("/{$resource_name}/{{$resource_id}}", [
            'as' => "{$resource_name}.destroy",
            'uses' => "{$controller}@destroy",
        ]);
    };

    $createRestResource($app, 'ProductController', 'products', 'productId');
    $createRestResource($app, 'SupplierController', 'suppliers', 'supplierId');
    $createRestResource($app, 'ClientController', 'clients', 'clientId');
    $createRestResource($app, 'MerchandiseController', 'merchandises', 'merchandiseId');
    $createRestResource($app, 'DeliveryAddressController', 'delivery_addresses', 'deliveryAddressId');
    $createRestResource($app, 'PurchaseController', 'purchases', 'purchaseId');
});
