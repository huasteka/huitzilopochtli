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
    $app->get('/products', [
        'as' => 'products.index',
        'uses' => 'ProductController@index',
    ]);
    $app->post('/products', [
        'as' => 'products.store',
        'uses' => 'ProductController@store',
    ]);
    $app->get('/products/{productId}', [
        'as' => 'products.show',
        'uses' => 'ProductController@show',
    ]);
    $app->put('/products/{productId}', [
        'as' => 'products.update',
        'uses' => 'ProductController@update',
    ]);
    $app->delete('/products/{productId}', [
        'as' => 'products.destroy',
        'uses' => 'ProductController@destroy',
    ]);

    $app->get('/suppliers', [
        'as' => 'suppliers.index',
        'uses' => 'SupplierController@index',
    ]);
    $app->post('/suppliers', [
        'as' => 'suppliers.store',
        'uses' => 'SupplierController@store',
    ]);
    $app->get('/suppliers/{supplierId}', [
        'as' => 'suppliers.show',
        'uses' => 'SupplierController@show',
    ]);
    $app->put('/suppliers/{supplierId}', [
        'as' => 'suppliers.update',
        'uses' => 'SupplierController@update',
    ]);
    $app->delete('/suppliers/{supplierId}', [
        'as' => 'suppliers.destroy',
        'uses' => 'SupplierController@destroy',
    ]);

    $app->get('/clients', [
        'as' => 'clients.index',
        'uses' => 'ClientController@index',
    ]);
    $app->post('/clients', [
        'as' => 'clients.store',
        'uses' => 'ClientController@store',
    ]);
    $app->get('/clients/{clientId}', [
        'as' => 'clients.show',
        'uses' => 'ClientController@show',
    ]);
    $app->put('/clients/{clientId}', [
        'as' => 'clients.update',
        'uses' => 'ClientController@update',
    ]);
    $app->delete('/clients/{clientId}', [
        'as' => 'clients.destroy',
        'uses' => 'ClientController@destroy',
    ]);
});
