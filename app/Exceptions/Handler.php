<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        if (app()->bound('sentry') && $this->shouldReport($e)) {
            app('sentry')->captureException($e);
        }
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $errors = [];
        if (is_a($e, ValidationException::class)) {
            foreach ($e->validator->errors()->getMessages() as $field => $messages) {
                foreach ($messages as $message) {
                    $error = [
                        'status' => '422',
                        'title' => 'Invalid Parameter',
                        'source' => [
                            'parameter' => $field
                        ],
                        'detail' => $message,
                    ];
                    if (is_null($request->query(str_replace('.', '_', $field)))) {
                        $error['source']['pointer'] = sprintf('/data/attributes/%s', $field);
                    }
                    $errors[] = $error;
                }
            }
        } else {
            $rendered = parent::render($request, $e);
            $errors[] = [
                'status' => $rendered->getStatusCode(),
                'code' => $e->getCode(),
                'title' => $e->getMessage(),
            ];
        }

        return response()->json([
            'errors' => $errors,
        ])->header('Content-Type', 'application/vnd.api+json');
    }
}
