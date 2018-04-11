<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;

class ExampleController extends Controller
{
    public function action()
    {
        return response()
            ->json()
            ->header('Content-Type', 'application/vnd.api+json')
            ->header('Access-Control-Allow-Origin', '*');
    }
}
