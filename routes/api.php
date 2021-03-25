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

/** @var Registrar $router */

use App\Http\Controllers\V1\ExampleController;
use Illuminate\Contracts\Routing\Registrar;

Route::get('/', function () {
    return app()->version();
});

Route::get('/status', function () {
    return ['message' => 'alive'];
});

Route::group([
    'prefix' => 'v4',
    'namespace' => 'V1',
    'middleware' => App\Http\Middleware\ArrayParserMiddleware::class,
], function () {
    Route::get('/example', [ExampleController::class, 'action']);
});
