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

$productControllerClass = App\Http\Controllers\ProductController::class;
$app->post('/api/v1/products', 'ProductController@create');
$app->put('/api/v1/products/{productId}', 'ProductController@update');
$app->delete('/api/v1/products/{productId}', 'ProductController@destroy');
$app->get('/api/v1/products/{productId}', 'ProductController@findOne');
$app->get('/api/v1/products', 'ProductController@findAll');
