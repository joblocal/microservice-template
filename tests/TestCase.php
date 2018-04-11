<?php

namespace Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use Tests\Traits\DatabaseMigrations;

class TestCase extends \Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    /**
     * Runs custom traits when they are being used
     */
    protected function setUpTraits()
    {
        parent::setUpTraits();

        $uses = array_flip(class_uses_recursive(get_class($this)));

        if (isset($uses[DatabaseMigrations::class])) {
            $this->runDatabaseMigrations();
        }
    }

    /**
     * mocks the result of a remote api GET request
     */
    protected function mockRemoteApi()
    {
        $this->app->bind('guzzle', function () {

            $jsonFilePath = dirname(__FILE__) . '/models/Remote/mocks/apiStub.json';
            $jsonResponse = file_get_contents($jsonFilePath);

            $mock = new MockHandler([
                new Response(200, [], $jsonResponse),
            ]);
            $handler = HandlerStack::create($mock);

            $config = array_merge(config('guzzle'), ['handler' => $handler]);
            $client = new Client($config);

            return $client;
        });
    }

    /**
     * stubs remote api response
     * @return [array] mock data as array
     */
    protected function getStubRemoteApi()
    {
        $jsonFilePath = dirname(__FILE__) . '/models/Remote/mocks/apiStub.json';
        $jsonData = json_decode(file_get_contents($jsonFilePath), true);
        return $jsonData;
    }
}
