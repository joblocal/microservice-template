<?php

namespace Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    /**
     * mocks the result of a remote api GET request
     */
    protected function mockRemoteApi(): void
    {
        $this->app->bind('guzzle', function () {
            $jsonFilePath = __DIR__.'/Models/Remote/mocks/apiStub.json';
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
     *
     * @return array mock data as array
     */
    protected function getStubRemoteApi()
    {
        $jsonFilePath = __DIR__.'/Models/Remote/mocks/apiStub.json';

        return json_decode(file_get_contents($jsonFilePath), true, 512, JSON_THROW_ON_ERROR);
    }
}
