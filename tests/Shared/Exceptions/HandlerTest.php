<?php

namespace Tests\Shared\Exceptions;

use App\Exceptions\Handler;
use App\Exceptions\ResourceValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;
use Mockery;
use Tests\TestCase;

class HandlerTest extends TestCase
{
    public function testRender()
    {
        $exception = new Exception('test exception', 123);
        $request = new Request();
        $handler = resolve(Handler::class);

        $result = $handler->render($request, $exception);
        self::assertEquals([
            'errors' => [
                [
                    'status' => 500,
                    'code' => 123,
                    'title' => 'test exception'
                ]
            ]
        ], $result->getData(true));
    }

    public function testRenderValidationException()
    {
        $validator = Mockery::mock()
            ->shouldReceive('errors')
            ->andReturn(new MessageBag(['first' => 'message 1', 'second' => 'message 2']))
            ->once()
            ->getMock();
        $exception = new ValidationException($validator);
        $request = new Request(['first' => 'value']);
        $handler = resolve(Handler::class);

        $result = $handler->render($request, $exception);
        self::assertEquals([
            'errors' => [
                [
                    'status' => '422',
                    'title' => 'Invalid Parameter',
                    'source' => [
                        'parameter' => 'first'
                    ],
                    'detail' => 'message 1'
                ],
                [
                    'status' => '422',
                    'title' => 'Invalid Parameter',
                    'source' => [
                        'parameter' => 'second'
                    ],
                    'detail' => 'message 2'
                ]
            ]
        ], $result->getData(true));
    }

    public function testRenderSourcePointer()
    {
        $validator = Mockery::mock()
            ->shouldReceive('errors')
            ->andReturn(new MessageBag(['first' => 'message 1']))
            ->once()
            ->getMock();
        $request = new Request(['nested_param' => 'value']);
        $exception = new ResourceValidationException($validator);
        $handler = resolve(Handler::class);

        $result = $handler->render($request, $exception);

        self::assertEquals([
            'errors' => [
                [
                    'status' => '422',
                    'title' => 'Invalid Parameter',
                    'source' => [
                        'parameter' => 'first',
                        'pointer' => '/data/attributes/first'
                    ],
                    'detail' => 'message 1'
                ],
            ]
        ], $result->getData(true));
    }
}
