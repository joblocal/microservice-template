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
$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

$router->group([
    'prefix' => 'v4',
    'namespace' => 'V1',
    'middleware' => App\Http\Middleware\ArrayParserMiddleware::class,
], function () use ($router) {
    $router->get('example', [
        'as' => 'example',
        'uses' => 'ExampleController@action',
    ]);
});
