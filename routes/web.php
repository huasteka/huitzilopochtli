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

$app->post('/api/v1/products', 'ProductController@create');
$app->put('/api/v1/products/{productId}', 'ProductController@update');
$app->delete('/api/v1/products/{productId}', 'ProductController@destroy');
$app->get('/api/v1/products/{productId}', 'ProductController@findOne');
$app->get('/api/v1/products', 'ProductController@findAll');

$app->post('/api/v1/suppliers', 'SupplierController@create');
$app->put('/api/v1/suppliers/{supplierId}', 'SupplierController@update');
$app->delete('/api/v1/suppliers/{supplierId}', 'SupplierController@destroy');
$app->get('/api/v1/suppliers/{supplierId}', 'SupplierController@findOne');
$app->get('/api/v1/suppliers', 'SupplierController@findAll');
