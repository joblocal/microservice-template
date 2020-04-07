<?php

namespace Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Laravel\Lumen\Application;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    /**
     * mocks the result of a remote api GET request
     */
    protected function mockRemoteApi(): void
    {
        $this->app->bind('guzzle', function () {
            $jsonFilePath = __DIR__ . '/Models/Remote/mocks/apiStub.json';
            $jsonResponse = file_get_contents($jsonFilePath);

            $mock = new MockHandler([
                new Response(200, [], $jsonResponse),
            ]);
            $handler = HandlerStack::create($mock);

            $config = array_merge(config('guzzle'), ['handler' => $handler]);

            return new Client($config);
        });
    }

    /**
     * stubs remote api response
     * @return [array] mock data as array
     */
    protected function getStubRemoteApi()
    {
        $jsonFilePath = __DIR__ . '/Models/Remote/mocks/apiStub.json';

        return json_decode(file_get_contents($jsonFilePath), true);
    }
}
