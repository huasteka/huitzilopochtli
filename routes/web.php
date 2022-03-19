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

$router->get('/', function () {
    return Illuminate\Support\Facades\File::get(base_path('public') . '/index.html');
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $createRestResource = function (Laravel\Lumen\Routing\Router $router, $controller, $resource_name, $resource_id, callable $routes = null) {
        $router->get("/{$resource_name}", [
            'as' => "{$resource_name}.index",
            'uses' => "{$controller}@index",
        ]);
        $router->post("/{$resource_name}", [
            'as' => "{$resource_name}.store",
            'uses' => "{$controller}@store",
        ]);
        $router->get("/{$resource_name}/{{$resource_id}}", [
            'as' => "{$resource_name}.show",
            'uses' => "{$controller}@show",
        ]);
        $router->put("/{$resource_name}/{{$resource_id}}", [
            'as' => "{$resource_name}.update",
            'uses' => "{$controller}@update",
        ]);
        $router->delete("/{$resource_name}/{{$resource_id}}", [
            'as' => "{$resource_name}.destroy",
            'uses' => "{$controller}@destroy",
        ]);
        if (!is_null($routes)) {
            $routes($router, $controller, $resource_name, $resource_id);
        }
    };

    $createContactableResource = function (Laravel\Lumen\Routing\Router $router, $controller, $resource_name, $resource_id) {
        $router->post("/{$resource_name}/{{$resource_id}}/contacts", [
            'as' => "{$resource_name}.contacts.store",
            'uses' => "{$controller}@storeContact"
        ]);
        $router->put("/{$resource_name}/{{$resource_id}}/contacts/{contactId}", [
            'as' => "{$resource_name}.contacts.update",
            'uses' => "{$controller}@updateContact"
        ]);
        $router->delete("/{$resource_name}/{{$resource_id}}/contacts/{contactId}", [
            'as' => "{$resource_name}.contacts.destroy",
            'uses' => "{$controller}@destroyContact"
        ]);
    };

    $createRestResource($router, 'ProductController', 'products', 'productId');
    $router->get('/products/{productCode}/details', [
        'as' => 'products.find-by-code', 
        'uses' => 'ProductController@findByCode'
    ]);
    $createRestResource($router, 'SupplierController', 'suppliers', 'supplierId', function ($router, $controller, $resource_name, $resource_id) use ($createContactableResource) {
        $createContactableResource($router, $controller, $resource_name, $resource_id);
    });
    $createRestResource($router, 'ClientController', 'clients', 'clientId', function ($router, $controller, $resource_name, $resource_id) use ($createContactableResource) {
        $createContactableResource($router, $controller, $resource_name, $resource_id);
    });
    $createRestResource($router, 'MerchandiseController', 'merchandises', 'merchandiseId');
    $createRestResource($router, 'DeliveryAddressController', 'delivery_addresses', 'deliveryAddressId');
    $createRestResource($router, 'PurchaseController', 'purchases', 'purchaseId');
    $createRestResource($router, 'SaleController', 'sales', 'saleId');
});
