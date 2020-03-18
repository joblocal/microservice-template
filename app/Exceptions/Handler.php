<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

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
     * @param Throwable $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return Response
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        $errors = [];

        if (is_a($exception, ValidationException::class)) {
            $statusCode = 422;

            foreach ($exception->validator->errors()->getMessages() as $field => $messages) {
                foreach ($messages as $message) {
                    $error = [
                        'status' => (string)$statusCode,
                        'title' => 'Invalid Parameter',
                        'source' => [
                            'parameter' => $field
                        ],
                        'detail' => $message,
                    ];
                    if (is_a($exception, ResourceValidationException::class)) {
                        $error['source']['pointer'] = sprintf('/data/attributes/%s', $field);
                    }
                    $errors[] = $error;
                }
            }
        } else {
            $rendered = parent::render($request, $exception);
            $statusCode = $rendered->getStatusCode();
            $errors[] = [
                'status' => $statusCode,
                'code' => $exception->getCode(),
                'title' => $exception->getMessage(),
            ];
        }

        return response()
            ->json([
                'errors' => $errors,
            ])
            ->header('Content-Type', 'application/vnd.api+json')
            ->setStatusCode($statusCode);
    }
}
